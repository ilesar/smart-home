<?php

namespace App\Mqtt\Model\Base;

use App\Mqtt\MqttService;
use Doctrine\ORM\EntityManagerInterface;

class BaseSubscriber
{
    /**
     * @var MqttService
     */
    private $mqttService;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(MqttService $mqttService, EntityManagerInterface $entityManager)
    {
        $this->mqttService = $mqttService;
        $this->entityManager = $entityManager;
    }

    public function getEntityManager(): EntityManagerInterface
    {
        return $this->entityManager;
    }

    public function setEntityManager(EntityManagerInterface $entityManager): void
    {
        $this->entityManager = $entityManager;
    }

    public function getMqttService(): MqttService
    {
        return $this->mqttService;
    }

    public function setMqttService(MqttService $mqttService): void
    {
        $this->mqttService = $mqttService;
    }
}
