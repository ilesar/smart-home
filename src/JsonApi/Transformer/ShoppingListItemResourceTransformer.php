<?php

namespace App\JsonApi\Transformer;

use App\Entity\ShoppingListItem;
use App\Service\ConfigurationService;
use WoohooLabs\Yin\JsonApi\Schema\Link\Link;
use WoohooLabs\Yin\JsonApi\Schema\Link\ResourceLinks;
use WoohooLabs\Yin\JsonApi\Schema\Relationship\ToOneRelationship;
use WoohooLabs\Yin\JsonApi\Schema\Resource\AbstractResource;

/**
 * ShoppingListItem Resource Transformer.
 */
class ShoppingListItemResourceTransformer extends AbstractResource
{
    /**
     * @var ConfigurationService
     */
    private $configuration;

    public function __construct(ConfigurationService $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * {@inheritdoc}
     */
    public function getType($shoppingListItem): string
    {
        return 'shopping_list_items';
    }

    /**
     * {@inheritdoc}
     */
    public function getId($shoppingListItem): string
    {
        return (string) $shoppingListItem->getId();
    }

    /**
     * {@inheritdoc}
     */
    public function getMeta($shoppingListItem): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getLinks($shoppingListItem): ?ResourceLinks
    {
        return ResourceLinks::createWithoutBaseUri()->setSelf(new Link('/shopping/list/items/'.$this->getId($shoppingListItem)));
    }

    /**
     * {@inheritdoc}
     */
    public function getAttributes($shoppingListItem): array
    {
        return [
            'quantity' => function (ShoppingListItem $shoppingListItem) {
                return $shoppingListItem->getQuantity();
            },
            'createdAt' => function (ShoppingListItem $shoppingListItem) {
                return $shoppingListItem->getCreatedAt()->format(DATE_ATOM);
            },
            'updatedAt' => function (ShoppingListItem $shoppingListItem) {
                return $shoppingListItem->getUpdatedAt()->format(DATE_ATOM);
            },
            'isResolved' => function (ShoppingListItem $shoppingListItem) {
                return $shoppingListItem->getIsResolved();
            },
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultIncludedRelationships($shoppingListItem): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getRelationships($shoppingListItem): array
    {
        return [
            'groceryItem' => function (ShoppingListItem $shoppingListItem) {
                return ToOneRelationship::create()
                    ->setDataAsCallable(
                        function () use ($shoppingListItem) {
                            return $shoppingListItem->getGroceryItem();
                        },
                        new GroceryItemResourceTransformer($this->configuration)
                    )
                    ->omitDataWhenNotIncluded();
            },
        ];
    }
}
