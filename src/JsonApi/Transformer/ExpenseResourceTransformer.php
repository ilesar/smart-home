<?php

namespace App\JsonApi\Transformer;

use App\Entity\Expense;
use WoohooLabs\Yin\JsonApi\Schema\Link\Link;
use WoohooLabs\Yin\JsonApi\Schema\Link\ResourceLinks;
use WoohooLabs\Yin\JsonApi\Schema\Relationship\ToOneRelationship;
use WoohooLabs\Yin\JsonApi\Schema\Resource\AbstractResource;

/**
 * Expense Resource Transformer.
 */
class ExpenseResourceTransformer extends AbstractResource
{
    /**
     * {@inheritdoc}
     */
    public function getType($expense): string
    {
        return 'expenses';
    }

    /**
     * {@inheritdoc}
     */
    public function getId($expense): string
    {
        return (string) $expense->getId();
    }

    /**
     * {@inheritdoc}
     */
    public function getMeta($expense): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getLinks($expense): ?ResourceLinks
    {
        return ResourceLinks::createWithoutBaseUri()->setSelf(new Link('/expenses/'.$this->getId($expense)));
    }

    /**
     * {@inheritdoc}
     */
    public function getAttributes($expense): array
    {
        return [
            'name' => function (Expense $expense) {
                return $expense->getName();
            },
            'price' => function (Expense $expense) {
                return $expense->getPrice();
            },
            'dueDate' => function (Expense $expense) {
                return $expense->getDueDate()->format(DATE_ATOM);
            },
            'createdAt' => function (Expense $expense) {
                return $expense->getCreatedAt()->format(DATE_ATOM);
            },
            'updatedAt' => function (Expense $expense) {
                return $expense->getUpdatedAt()->format(DATE_ATOM);
            },
            'isResolved' => function (Expense $expense) {
                return $expense->getIsResolved();
            },
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultIncludedRelationships($expense): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getRelationships($expense): array
    {
        return [
            'recurringPayment' => function (Expense $expense) {
                return ToOneRelationship::create()
                    ->setDataAsCallable(
                        function () use ($expense) {
                            return $expense->getRecurringPayment();
                        },
                        new RecurringPaymentResourceTransformer()
                    )
                    ->omitDataWhenNotIncluded();
            },
        ];
    }
}
