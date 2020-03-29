<?php

namespace App\Validator\Constraint;

use App\Validator\LoginValidator;
use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class LoginConstraint extends Constraint
{
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }

    public function validatedBy(): string
    {
        return LoginValidator::class;
    }
}
