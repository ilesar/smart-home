<?php

namespace App\Mqtt\Enum;

class MqttTopic
{
    // INCOMING TOPICS

    public const DEVICE_REGISTRATION = '';
    public const DEVICE_CONFIGURATION = '';
    public const MEASUREMENT = '';

    // OUTGOING TOPICS

    public const CONFIGURATION_TEMPLATE = '';
    public const MEASUREMENT_ON_DEMAND_REQUEST = '';
}
