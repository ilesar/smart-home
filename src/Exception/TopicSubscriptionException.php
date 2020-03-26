<?php

namespace App\Exception;

use Exception;

class TopicSubscriptionException extends Exception
{
    public function __construct(string $topic)
    {
        parent::__construct(vsprintf('Already subscribed to topic (%s)', [
            $topic,
        ]));
    }
}
