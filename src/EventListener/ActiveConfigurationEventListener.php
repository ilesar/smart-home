<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Entity\ConfigurationTemplate;
use App\Repository\ConfigurationTemplateRepository;
use App\Service\MqttService;
use Doctrine\ORM\EntityManagerInterface;

class ActiveConfigurationEventListener
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var MqttService
     */
    private $mqttService;

    public function __construct(EntityManagerInterface $entityManager, MqttService $mqttService)
    {
        $this->entityManager = $entityManager;
        $this->mqttService = $mqttService;
    }

    public function preUpdate(ConfigurationTemplate $template): void
    {
        if (false === $this->checkIfActiveValueHasChanged($template)) {
            return;
        }

        $this->deactivateOtherTemplates($template);
        $this->sendTemplateToDevice($template);
    }

    private function checkIfActiveValueHasChanged(ConfigurationTemplate $template): bool
    {
        $changes = $this->getChangesFromEntity($template);

        return !(null === $changes || $changes[0] === $changes[1]);
    }

    private function deactivateOtherTemplates(ConfigurationTemplate $template)
    {
        /** @var ConfigurationTemplateRepository $repository */
        $repository = $this->entityManager->getRepository(ConfigurationTemplate::class);

        $repository->createQueryBuilder('t')
            ->update()
            ->set('t.isActive', '0')
            ->where('t.isActive = true')
            ->andWhere('t.configuration = :val')
            ->setParameter('val', $template->getConfiguration())
            ->getQuery()
            ->execute();
    }

    private function getChangesFromEntity(ConfigurationTemplate $template)
    {
        if (!$template instanceof ConfigurationTemplate) {
            return false;
        }

        $changeSet = $this->entityManager->getUnitOfWork()->getEntityChangeSet($template);

        return isset($changeSet['isActive']) ? $changeSet['isActive'] : null;
    }

    private function sendTemplateToDevice(ConfigurationTemplate $template)
    {
        $topic = $template->getConfiguration()->getDevice()->getDeviceId();
        $message = $this->getSerializedConfiguration($template);

        $this->mqttService->sendMessage($topic.'/config', $message);
        $this->mqttService->close();
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
