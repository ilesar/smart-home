<?php

namespace App\JsonApi\Hydrator\Expense;

use App\Entity\Expense;
use DateTimeImmutable;

/**
 * Create Expense Hydrator.
 */
class CreateExpenseHydrator extends AbstractExpenseHydrator
{
    /**
     * {@inheritdoc}
     */
    protected function getAttributeHydrator($expense): array
    {
        return [
            'dueDate' => function (Expense $expense, $attribute, $data, $attributeName) {
                $expense->setDueDate(new DateTimeImmutable($attribute));
            },
            'createdAt' => function (Expense $expense, $attribute, $data, $attributeName) {
                $expense->setCreatedAt(new \DateTime($attribute));
            },
            'updatedAt' => function (Expense $expense, $attribute, $data, $attributeName) {
                $expense->setUpdatedAt(new \DateTime($attribute));
            },
            'isResolved' => function (Expense $expense, $attribute, $data, $attributeName) {
                $expense->setIsResolved($attribute);
            },
        ];
    }
}
