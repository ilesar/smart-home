<?php

namespace App\JsonApi\Hydrator\Expense;

use App\Entity\Expense;

/**
 * Update Expense Hydrator.
 */
class UpdateExpenseHydrator extends AbstractExpenseHydrator
{
    /**
     * {@inheritdoc}
     */
    protected function getAttributeHydrator($expense): array
    {
        return [
            'name' => function (Expense $expense, $attribute, $data, $attributeName) {
                $expense->setName($attribute);
            },
            'price' => function (Expense $expense, $attribute, $data, $attributeName) {
                $expense->setPrice($attribute);
            },
            'dueDate' => function (Expense $expense, $attribute, $data, $attributeName) {
                $expense->setDueDate(new \DateTime($attribute));
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
