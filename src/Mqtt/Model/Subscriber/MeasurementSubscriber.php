<?php


namespace App\Mqtt\Model;


use App\Mqtt\Enum\MqttTopic;
use App\Mqtt\Interfaces\TopicSubscriberInterface;
use App\Mqtt\Model\Base\BaseSubscriber;
use Doctrine\ORM\EntityManagerInterface;

class MeasurementSubscriber extends BaseSubscriber implements TopicSubscriberInterface
{

    public function getTopic(): string
    {
        return MqttTopic::MEASUREMENT;
    }

    public function onMessageReceived(string $topic, string $message): void
    {
        // TODO: Implement onMessageReceived() method.
    }
}
