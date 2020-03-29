<?php

declare(strict_types=1);

namespace App\Model;

use App\Entity\User;
use DateTime;

class JsonWebToken
{
    public const TOKEN_TYPE_BEARER = 'Bearer';

    /** @var string */
    private $accessToken;

    /** @var string */
    private $tokenType;

    /** @var DateTime */
    private $expiresAt;

    /** @var User */
    private $user;

    public function __construct(User $user, DateTime $expiresAt, string $accessToken)
    {
        $this->accessToken = $accessToken;
        $this->tokenType = self::TOKEN_TYPE_BEARER;
        $this->expiresAt = $expiresAt;
        $this->user = $user;
    }

    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    public function getTokenType(): string
    {
        return $this->tokenType;
    }

    public function getExpiresAt(): DateTime
    {
        return $this->expiresAt;
    }

    public function getUser(): User
    {
        return $this->user;
    }
}
