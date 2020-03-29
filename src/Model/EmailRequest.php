<?php

declare(strict_types=1);

namespace App\Model;

use App\Validator\Constraint\EmailRequestConstraint;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\GroupSequenceProviderInterface;

/**
 * @EmailRequestConstraint(groups={"Extended"})
 * @Assert\GroupSequenceProvider()
 */
class EmailRequest implements GroupSequenceProviderInterface
{
    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email = '';

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function setAttributes(array $attributes): void
    {
        $this->setEmail($attributes['email'] ?? '');
    }

    public function getGroupSequence()
    {
        return [
            'EmailRequest',
            'Extended',
        ];
    }
}
