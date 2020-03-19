<?php

namespace App\JsonApi\Hydrator\ShoppingListItem;

use App\Entity\ShoppingListItem;

/**
 * Update ShoppingListItem Hydrator.
 */
class UpdateShoppingListItemHydrator extends AbstractShoppingListItemHydrator
{
    /**
     * {@inheritdoc}
     */
    protected function getAttributeHydrator($shoppingListItem): array
    {
        return [
            'quantity' => function (ShoppingListItem $shoppingListItem, $attribute, $data, $attributeName) {
                $shoppingListItem->setQuantity($attribute);
            },
            'createdAt' => function (ShoppingListItem $shoppingListItem, $attribute, $data, $attributeName) {
                $shoppingListItem->setCreatedAt(new \DateTime($attribute));
            },
            'updatedAt' => function (ShoppingListItem $shoppingListItem, $attribute, $data, $attributeName) {
                $shoppingListItem->setUpdatedAt(new \DateTime($attribute));
            },
            'isDeleted' => function (ShoppingListItem $shoppingListItem, $attribute, $data, $attributeName) {
                $shoppingListItem->setIsDeleted($attribute);
            },
        ];
    }
}
