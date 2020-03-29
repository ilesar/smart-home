<?php

namespace App\JsonApi\Hydrator\GroceryItem;

use App\Entity\GroceryItem;

/**
 * Update GroceryItem Hydrator.
 */
class UpdateGroceryItemHydrator extends AbstractGroceryItemHydrator
{
    /**
     * {@inheritdoc}
     */
    protected function getAttributeHydrator($groceryItem): array
    {
        return [
            'name' => function (GroceryItem $groceryItem, $attribute, $data, $attributeName) {
                $groceryItem->setName($attribute);
            },
            'description' => function (GroceryItem $groceryItem, $attribute, $data, $attributeName) {
                $groceryItem->setDescription($attribute);
            },
            'price' => function (GroceryItem $groceryItem, $attribute, $data, $attributeName) {
                $groceryItem->setPrice($attribute);
            },
            'createdAt' => function (GroceryItem $groceryItem, $attribute, $data, $attributeName) {
                $groceryItem->setCreatedAt(new \DateTime($attribute));
            },
            'updatedAt' => function (GroceryItem $groceryItem, $attribute, $data, $attributeName) {
                $groceryItem->setUpdatedAt(new \DateTime($attribute));
            },
            'source' => function (GroceryItem $groceryItem, $attribute, $data, $attributeName) {
                $groceryItem->setSource($attribute);
            },
        ];
    }
}
