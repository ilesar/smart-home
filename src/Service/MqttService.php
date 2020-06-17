<?php

namespace App\Service;

use Exception;
use PhpMqtt\Client\MQTTClient;

class MqttService
{
    /**
     * @var MQTTClient
     */
    private $mqttClient;

    public function __construct(MQTTClient $mqttClient)
    {
        $this->mqttClient = $mqttClient;

//        pcntl_signal(SIGINT, function (int $signal, $info) use ($mqttClient) {
//            $mqttClient->interrupt();
//        });

        $this->mqttClient->connect();
    }

    public function sendMessage(string $topic, string $message): void
    {
        try {
            $this->mqttClient->publish($topic, $message);
        } catch (Exception $exception) {
            echo $exception->getMessage().PHP_EOL;
        }
    }

    public function close(): void
    {
        $this->mqttClient->close();
    }
}
