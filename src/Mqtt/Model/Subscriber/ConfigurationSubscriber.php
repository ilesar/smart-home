<?php

namespace App\Mqtt\Model;

use App\Mqtt\Enum\MqttTopic;
use App\Mqtt\Interfaces\TopicSubscriberInterface;
use App\Mqtt\Model\Base\BaseSubscriber;

class ConfigurationSubscriber extends BaseSubscriber implements TopicSubscriberInterface
{
    public function getTopic(): string
    {
        return MqttTopic::DEVICE_CONFIGURATION;
    }

    public function onMessageReceived(string $topic, string $message): void
    {
        // TODO: Implement onMessageReceived() method.
    }
}
