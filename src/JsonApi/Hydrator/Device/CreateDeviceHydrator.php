<?php

namespace App\JsonApi\Hydrator\Device;

use App\Entity\Device;

/**
 * Create Device Hydrator.
 */
class CreateDeviceHydrator extends AbstractDeviceHydrator
{
    /**
     * {@inheritdoc}
     */
    protected function getAttributeHydrator($device): array
    {
        return [
            'name' => function (Device $device, $attribute, $data, $attributeName) {
                $device->setName($attribute);
            },
            'createdAt' => function (Device $device, $attribute, $data, $attributeName) {
                $device->setCreatedAt(new \DateTime($attribute));
            },
            'updatedAt' => function (Device $device, $attribute, $data, $attributeName) {
                $device->setUpdatedAt(new \DateTime($attribute));
            },
        ];
    }
}
