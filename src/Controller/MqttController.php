<?php

namespace App\Controller;

use App\Entity\Configuration;
use App\Entity\ConfigurationItem;
use App\Entity\ConfigurationTemplate;
use App\Entity\ConfigurationTemplateItem;
use App\Entity\Device;
use App\Enum\ConfigurationItemType;
use App\Repository\DeviceRepository;
use App\Service\MqttService;
use Doctrine\ORM\EntityManagerInterface;
use Paknahad\JsonApiBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/mqtt")
 */
class MqttController extends Controller
{
    /**
     * @Route("/configuration", name="mqtt_index", methods="POST")
     */
    public function index(DeviceRepository $deviceRepository, MqttService $mqttService, Request $request, EntityManagerInterface $entityManager)
    {
        $deviceId = $request->request->get('deviceId');

        $device = $deviceRepository->findOneBy([
            'deviceId' => $deviceId,
        ]);

        if (null === $device) {
            $configuration = $request->request->get('configuration');
            $configurationArray = json_decode($configuration);

            $device = new Device();
            $device->setDeviceId($deviceId);
            $configuration = new Configuration();
            $configurationTemplate = new ConfigurationTemplate();
            $configurationTemplate->setName('Default');
            $configurationTemplate->setIsActive(true);

            foreach ($configurationArray as $iterator => $configurationObject) {
                if (ConfigurationItemType::COLOR === $configurationObject->type) {
                    $configurationItem = new ConfigurationItem();
                    $configurationItem->setName(sprintf('LED #%s color', $iterator + 1));
                    $configurationItem->setDescription(sprintf('Kontrola boje za LED svijetlo na traci na poziciji %s', $iterator + 1));
                    $configurationItem->setInputType(ConfigurationItemType::COLOR);
                    $configurationItem->setDefaultValue(vsprintf('#%s%s%s', [
                        dechex($configurationObject->r),
                        dechex($configurationObject->g),
                        dechex($configurationObject->b),
                    ]));

                    $configurationItem->setOutputFormat('%s');

                    $entityManager->persist($configurationItem);
                    $configuration->addItem($configurationItem);

                    $configurationTemplateItem = new ConfigurationTemplateItem();
                    $configurationTemplateItem->setConfigurationItem($configurationItem);
                    $configurationTemplateItem->setValue($configurationItem->getDefaultValue());

                    $entityManager->persist($configurationTemplateItem);
                    $configurationTemplate->addItem($configurationTemplateItem);
                }
            }

            $entityManager->persist($configurationTemplate);

            $configuration->addTemplate($configurationTemplate);
            $entityManager->persist($configuration);
            $device->setConfiguration($configuration);
            $entityManager->persist($device);

            $entityManager->flush();
        }

        $topic = $device->getDeviceId().'/config';
        $message = $this->getSerializedActiveTemplateFromDevice($device);

        $mqttService->sendMessage($topic, $message);

        return new Response('');
    }

    private function getSerializedActiveTemplateFromDevice(Device $device)
    {
        $template = $device->getActiveTemplate();

        return $this->getSerializedConfiguration($template);
    }

    private function getSerializedConfiguration(ConfigurationTemplate $template)
    {
        $serializedConfiguration = [];
        foreach ($template->getItems() as $configurationTemplateItem) {
            $serializedConfiguration[] = $this->hexToRgb($configurationTemplateItem->getValue());
        }

        return json_encode([
            'configs' => $serializedConfiguration,
        ]);
    }

    public function hexToRgb(string $hex)
    {
        $hex = str_replace('#', '', $hex);

        if (strlen($hex) > 3) {
            $color = str_split($hex, 2);
        } else {
            $color = str_split($hex);
        }

        return [
            'r' => hexdec($color[0]),
            'g' => hexdec($color[1]),
            'b' => hexdec($color[2]),
        ];
    }
}
