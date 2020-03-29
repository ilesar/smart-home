<?php

namespace App\Model;

use App\Validator\Constraint\LoginConstraint;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\GroupSequenceProviderInterface;

/**
 * @Assert\GroupSequenceProvider()
 * @LoginConstraint(groups={"Extended"})
 */
class LoginResource implements GroupSequenceProviderInterface
{
    /**
     * @var string
     * @Assert\NotBlank()
     */
    private $username = '';

    /**
     * @var string
     * @Assert\NotBlank()
     */
    private $password = '';

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function setAttributes(array $attributes): void
    {
        $this->setPassword($attributes['password'] ?? '');
        $this->setUsername($attributes['username'] ?? '');
    }

    public function getGroupSequence()
    {
        return [
            'LoginResource',
            'Extended',
        ];
    }
}
