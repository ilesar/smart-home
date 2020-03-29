<?php

declare(strict_types=1);

namespace App\Validator;

use Symfony\Component\Validator\ConstraintValidator;

abstract class BaseValidator extends ConstraintValidator
{
    protected const EMAIL_RESET_REQUEST_TTL = 3600;

    protected function createTokenIsSetConstraint(): void
    {
        $fieldName = 'token';
        $message = 'Token is required.';

        $this->createConstraint($fieldName, $message);
    }

    protected function createTokenIsNotExpiredConstraint(): void
    {
        $fieldName = 'token';
        $message = 'Confirmation token is expired.';

        $this->createConstraint($fieldName, $message);
    }

    protected function createTokenOwnerExistsConstraint(): void
    {
        $fieldName = 'token';
        $message = 'Confirmation token is invalid.';

        $this->createConstraint($fieldName, $message);
    }

    protected function createConstraint(string $fieldName, string $message): void
    {
        $this->context->buildViolation($message)
            ->setInvalidValue($fieldName)
            ->atPath($fieldName)
            ->addViolation();
    }
}
