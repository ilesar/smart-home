<?php

namespace App\JsonApi\Transformer;

use App\Entity\GroceryItem;
use WoohooLabs\Yin\JsonApi\Schema\Link\Link;
use WoohooLabs\Yin\JsonApi\Schema\Link\ResourceLinks;
use WoohooLabs\Yin\JsonApi\Schema\Relationship\ToManyRelationship;
use WoohooLabs\Yin\JsonApi\Schema\Relationship\ToOneRelationship;
use WoohooLabs\Yin\JsonApi\Schema\Resource\AbstractResource;

/**
 * GroceryItem Resource Transformer.
 */
class GroceryItemResourceTransformer extends AbstractResource
{
    /**
     * {@inheritdoc}
     */
    public function getType($groceryItem): string
    {
        return 'grocery_items';
    }

    /**
     * {@inheritdoc}
     */
    public function getId($groceryItem): string
    {
        return (string) $groceryItem->getId();
    }

    /**
     * {@inheritdoc}
     */
    public function getMeta($groceryItem): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getLinks($groceryItem): ?ResourceLinks
    {
        return ResourceLinks::createWithoutBaseUri()->setSelf(new Link('/grocery/items/'.$this->getId($groceryItem)));
    }

    /**
     * {@inheritdoc}
     */
    public function getAttributes($groceryItem): array
    {
        return [
            'name' => function (GroceryItem $groceryItem) {
                return $groceryItem->getName();
            },
            'description' => function (GroceryItem $groceryItem) {
                return $groceryItem->getDescription();
            },
            'price' => function (GroceryItem $groceryItem) {
                return $groceryItem->getPrice();
            },
            'createdAt' => function (GroceryItem $groceryItem) {
                return $groceryItem->getCreatedAt()->format(DATE_ATOM);
            },
            'updatedAt' => function (GroceryItem $groceryItem) {
                return $groceryItem->getUpdatedAt()->format(DATE_ATOM);
            },
            'isDeleted' => function (GroceryItem $shoppingListItem) {
                return $shoppingListItem->getIsDeleted();
            },
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultIncludedRelationships($groceryItem): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getRelationships($groceryItem): array
    {
        return [
            'shoppingListItems' => function (GroceryItem $groceryItem) {
                return ToManyRelationship::create()
                    ->setDataAsCallable(
                        function () use ($groceryItem) {
                            return $groceryItem->getShoppingListItems();
                        },
                        new ShoppingListItemResourceTransformer()
                    )
                    ->omitDataWhenNotIncluded();
            },
            'image' => function (GroceryItem $groceryItem) {
                return ToOneRelationship::create()
                    ->setDataAsCallable(
                        function () use ($groceryItem) {
                            return $groceryItem->getImage();
                        },
                        new ImageResourceTransformer()
                    )
                    ->omitDataWhenNotIncluded();
            },
        ];
    }
}
