<?php

namespace App\Mqtt;

use App\Exception\TopicSubscriptionException;
use App\Exception\TopicUnsubscriptionException;
use App\Mqtt\Interfaces\TopicPublisherInterface;
use App\Mqtt\Interfaces\TopicSubscriberInterface;
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

    public function addTopicListener(TopicSubscriberInterface $subscriber)
    {
        $topic = $subscriber->getTopic();

        if (true === in_array($topic, $this->subscribedTopics)) {
            throw new TopicSubscriptionException($topic);
        }

        $this->mqttClient->subscribe($topic, function ($topic, $message) use ($subscriber) {
            $subscriber->onMessageReceived($topic, $message);
        }, 0);
    }

    public function removeTopicListener(TopicSubscriberInterface $subscriber)
    {
        $topic = $subscriber->getTopic();

        if (false === in_array($topic, $this->subscribedTopics)) {
            throw new TopicUnsubscriptionException($topic);
        }

        $this->mqttClient->unsubscribe($topic);
    }

    public function sendMessage(TopicPublisherInterface $publisher, $message)
    {
        $publisher->publishMessage($message);
    }
}
