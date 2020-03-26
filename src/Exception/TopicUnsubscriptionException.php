<?php

namespace App\Exception;

use Exception;

class TopicUnsubscriptionException extends Exception
{
    public function __construct(string $topic)
    {
        parent::__construct(vsprintf('Unsubscribing to an unexisting topic (%s)', [
            $topic,
        ]));
    }
}
