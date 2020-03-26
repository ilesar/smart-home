<?php

namespace App\Mqtt\Interfaces;

interface TopicPublisherInterface
{
    public function getTopic(): string;

    public function publishMessage(string $topic, string $message): void;
}