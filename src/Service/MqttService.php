<?php

namespace App\Service;

use App\Exception\TopicSubscriptionException;
use App\Exception\TopicUnsubscriptionException;
use Closure;
use PhpMqtt\Client\MQTTClient;

class MqttService
{
    /**
     * @var MQTTClient
     */
    private $mqttClient;

    /**
     * @var string[]
     */
    private $subscribedTopics = [];

    public function __construct(MQTTClient $mqttClient)
    {
        $this->mqttClient = $mqttClient;

        pcntl_signal(SIGINT, function (int $signal, $info) use ($mqttClient) {
            $mqttClient->interrupt();
        });

        $this->mqttClient->connect();
    }

    public function run()
    {
        $this->mqttClient->loop(true);
        $this->mqttClient->close();
    }

    public function addTopicListener(string $topic, Closure $callback)
    {
        if (true === in_array($topic, $this->subscribedTopics)) {
            throw new TopicSubscriptionException($topic);
        }

        $this->mqttClient->subscribe($topic, function ($topic, $message) use ($callback) {
            $callback($topic, $message);
        }, 0);
    }

    public function removeTopicListener(string $topic, Closure $callback)
    {
        if (false === in_array($topic, $this->subscribedTopics)) {
            throw new TopicUnsubscriptionException($topic);
        }

        $this->mqttClient->subscribe($topic, function ($topic, $message) use ($callback) {
            $callback($topic, $message);
        }, 0);
    }

    public function sendMessage(string $topic, string $message)
    {
        $this->mqttClient->publish($topic, $message, 0);
    }
}
