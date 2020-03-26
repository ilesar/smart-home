<?php

namespace App\Mqtt\Interfaces;

interface TopicSubscriberInterface
{
    public function getTopic(): string;

    public function onMessageReceived(string $topic, string $message): void;
}
