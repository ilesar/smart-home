<?php

namespace App\JsonApi\Transformer;

use App\Entity\RecurringPayment;
use WoohooLabs\Yin\JsonApi\Schema\Link\Link;
use WoohooLabs\Yin\JsonApi\Schema\Link\ResourceLinks;
use WoohooLabs\Yin\JsonApi\Schema\Relationship\ToManyRelationship;
use WoohooLabs\Yin\JsonApi\Schema\Resource\AbstractResource;

/**
 * RecurringPayment Resource Transformer.
 */
class RecurringPaymentResourceTransformer extends AbstractResource
{
    /**
     * {@inheritdoc}
     */
    public function getType($recurringPayment): string
    {
        return 'recurring_payments';
    }

    /**
     * {@inheritdoc}
     */
    public function getId($recurringPayment): string
    {
        return (string) $recurringPayment->getId();
    }

    /**
     * {@inheritdoc}
     */
    public function getMeta($recurringPayment): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getLinks($recurringPayment): ?ResourceLinks
    {
        return ResourceLinks::createWithoutBaseUri()->setSelf(new Link('/recurring/payments/'.$this->getId($recurringPayment)));
    }

    /**
     * {@inheritdoc}
     */
    public function getAttributes($recurringPayment): array
    {
        return [
            'name' => function (RecurringPayment $recurringPayment) {
                return $recurringPayment->getName();
            },
            'price' => function (RecurringPayment $recurringPayment) {
                return $recurringPayment->getPrice();
            },
            'createdAt' => function (RecurringPayment $recurringPayment) {
                return $recurringPayment->getCreatedAt()->format(DATE_ATOM);
            },
            'updatedAt' => function (RecurringPayment $recurringPayment) {
                return $recurringPayment->getUpdatedAt()->format(DATE_ATOM);
            },
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultIncludedRelationships($recurringPayment): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getRelationships($recurringPayment): array
    {
        return [
            'expenses' => function (RecurringPayment $recurringPayment) {
                return ToManyRelationship::create()
                    ->setDataAsCallable(
                        function () use ($recurringPayment) {
                            return $recurringPayment->getExpenses();
                        },
                        new ExpenseResourceTransformer()
                    )
                    ->omitDataWhenNotIncluded();
            },
        ];
    }
}
