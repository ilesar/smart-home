<?php

namespace App\Validator\Constraint;

use App\Validator\HasExpenseValidator;
use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class HasExpenseConstraint extends Constraint
{
    /*
     * Any public properties become valid options for the annotation.
     * Then, use these in your validator class.
     */
    public $message = 'You cannot delete this payment because it has expenses.';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }

    public function validatedBy(): string
    {
        return HasExpenseValidator::class;
    }
}
