<?php


namespace App\Mqtt\Model\Base;


use App\Service\MqttService;
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

    /**
     * @return EntityManagerInterface
     */
    public function getEntityManager(): EntityManagerInterface
    {
        return $this->entityManager;
    }

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function setEntityManager(EntityManagerInterface $entityManager): void
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @return MqttService
     */
    public function getMqttService(): MqttService
    {
        return $this->mqttService;
    }

    /**
     * @param MqttService $mqttService
     */
    public function setMqttService(MqttService $mqttService): void
    {
        $this->mqttService = $mqttService;
    }
}
