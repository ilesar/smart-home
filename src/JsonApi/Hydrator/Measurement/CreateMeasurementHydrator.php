<?php

namespace App\JsonApi\Hydrator\Measurement;

use App\Entity\Measurement;

/**
 * Create Measurement Hydrator.
 */
class CreateMeasurementHydrator extends AbstractMeasurementHydrator
{
    /**
     * {@inheritdoc}
     */
    protected function getAttributeHydrator($measurement): array
    {
        return [
            'name' => function (Measurement $measurement, $attribute, $data, $attributeName) {
                $measurement->setName($attribute);
            },
            'value' => function (Measurement $measurement, $attribute, $data, $attributeName) {
                $measurement->setValue($attribute);
            },
            'type' => function (Measurement $measurement, $attribute, $data, $attributeName) {
                $measurement->setType($attribute);
            },
            'createdAt' => function (Measurement $measurement, $attribute, $data, $attributeName) {
                $measurement->setCreatedAt(new \DateTime($attribute));
            },
            'updatedAt' => function (Measurement $measurement, $attribute, $data, $attributeName) {
                $measurement->setUpdatedAt(new \DateTime($attribute));
            },
        ];
    }
}
