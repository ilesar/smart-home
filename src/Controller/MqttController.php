<?php

namespace App\Controller;

use App\Entity\ConfigurationTemplate;
use App\Entity\Device;
use App\Repository\DeviceRepository;
use App\Service\MqttService;
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
    public function index(DeviceRepository $deviceRepository, MqttService $mqttService, Request $request)
    {
        $deviceId = $request->request->get('deviceId');

        $device = $deviceRepository->findOneBy([
            'deviceId' => $deviceId,
        ]);

        if (null === $device) {
            return new Response('NO DEVICE');
        }

        $topic = $device->getDeviceId();
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
