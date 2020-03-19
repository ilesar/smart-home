<?php

namespace App\JsonApi\Hydrator\Image;

use App\Entity\Image;

/**
 * Create Image Hydrator.
 */
class CreateImageHydrator extends AbstractImageHydrator
{
    /**
     * {@inheritdoc}
     */
    protected function getAttributeHydrator($image): array
    {
        return [
            'filename' => function (Image $image, $attribute, $data, $attributeName) {
                $image->setFilename($attribute);
            },
            'uuid' => function (Image $image, $attribute, $data, $attributeName) {
                $image->setUuid($attribute);
            },
            'createdAt' => function (Image $image, $attribute, $data, $attributeName) {
                $image->setCreatedAt(new \DateTime($attribute));
            },
            'updatedAt' => function (Image $image, $attribute, $data, $attributeName) {
                $image->setUpdatedAt(new \DateTime($attribute));
            },
        ];
    }
}
