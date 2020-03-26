<?php

namespace App\Mqtt\Model\Publisher;

use App\Mqtt\Enum\MqttTopic;
use App\Mqtt\Interfaces\TopicPublisherInterface;
use App\Mqtt\Model\Base\BasePublisher;

class ConfigurationTemplatePublisher extends BasePublisher implements TopicPublisherInterface
{
    public function getTopic(): string
    {
        return MqttTopic::CONFIGURATION_TEMPLATE;
    }
}
