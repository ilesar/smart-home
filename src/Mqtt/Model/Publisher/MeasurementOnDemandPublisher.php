<?php


namespace App\Mqtt\Model;


use App\Mqtt\Enum\MqttTopic;
use App\Mqtt\Interfaces\TopicPublisherInterface;
use App\Mqtt\Interfaces\TopicSubscriberInterface;
use App\Mqtt\Model\Base\BasePublisher;
use App\Mqtt\Model\Base\BaseSubscriber;

class MeasurementOnDemandPublisher extends BasePublisher implements TopicPublisherInterface
{
    public function getTopic(): string
    {
        return MqttTopic::MEASUREMENT_ON_DEMAND_REQUEST;
    }
}
