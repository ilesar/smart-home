<?php

namespace App\JsonApi\Hydrator\RecurringPayment;

use App\Entity\RecurringPayment;

/**
 * Update RecurringPayment Hydrator.
 */
class UpdateRecurringPaymentHydrator extends AbstractRecurringPaymentHydrator
{
    /**
     * {@inheritdoc}
     */
    protected function getAttributeHydrator($recurringPayment): array
    {
        return [
            'name' => function (RecurringPayment $recurringPayment, $attribute, $data, $attributeName) {
                $recurringPayment->setName($attribute);
            },
            'price' => function (RecurringPayment $recurringPayment, $attribute, $data, $attributeName) {
                $recurringPayment->setPrice($attribute);
            },
            'period' => function (RecurringPayment $recurringPayment, $attribute, $data, $attributeName) {
                $recurringPayment->setPeriod($attribute);
            },
            'activationTime' => function (RecurringPayment $recurringPayment, $attribute, $data, $attributeName) {
                $recurringPayment->setActivationTime(new \DateTime($attribute));
            },
            'isAutomated' => function (RecurringPayment $recurringPayment, $attribute, $data, $attributeName) {
                $recurringPayment->setIsAutomated($attribute);
            },
            'paymentTag' => function (RecurringPayment $recurringPayment, $attribute, $data, $attributeName) {
                $recurringPayment->setPaymentTag($attribute);
            },
            'createdAt' => function (RecurringPayment $recurringPayment, $attribute, $data, $attributeName) {
                $recurringPayment->setCreatedAt(new \DateTime($attribute));
            },
            'updatedAt' => function (RecurringPayment $recurringPayment, $attribute, $data, $attributeName) {
                $recurringPayment->setUpdatedAt(new \DateTime($attribute));
            },
        ];
    }
}
