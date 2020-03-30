<?php

namespace App\Validator;

use App\Validator\Constraint\HasExpenseConstraint;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class HasExpenseValidator extends ConstraintValidator
{
    public function validate($recurringPayment, Constraint $constraint)
    {
        /* @var $constraint HasExpenseConstraint */

        if (null === $recurringPayment) {
            return;
        }

        if (count($recurringPayment->getExpenses()) > 0) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
