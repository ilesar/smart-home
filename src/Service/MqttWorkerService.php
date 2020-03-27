<?php


namespace App\Service;


use App\Mqtt\Interfaces\TopicSubscriberInterface;
use App\Mqtt\Model\ConfigurationSubscriber;
use App\Mqtt\Model\MeasurementSubscriber;
use App\Mqtt\Model\RegistrationSubscriber;
use App\Mqtt\MqttService;

class MqttWorkerService
{
    /**
     * @var MqttService
     */
    private $mqttService;
    /**
     * @var TopicSubscriberInterface[]
     */
    private $subscribers;

    public function __construct(MqttService $mqttService, $subscribers)
    {
        $this->mqttService = $mqttService;
        $this->subscribers = $subscribers;
    }

    public function run()
    {
        foreach ($this->subscribers as $subscriber) {
            $this->mqttService->addTopicListener($subscriber);
        }

        $this->mqttService->run();
    }
}
