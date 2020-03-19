<?php

namespace App\JsonApi\Hydrator\ShoppingListItem;

use App\Entity\ShoppingListItem;
use Paknahad\JsonApiBundle\Exception\InvalidRelationshipValueException;
use Paknahad\JsonApiBundle\Hydrator\AbstractHydrator;
use Paknahad\JsonApiBundle\Hydrator\ValidatorTrait;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface;
use WoohooLabs\Yin\JsonApi\Hydrator\Relationship\ToOneRelationship;
use WoohooLabs\Yin\JsonApi\Request\JsonApiRequestInterface;

/**
 * Abstract ShoppingListItem Hydrator.
 */
abstract class AbstractShoppingListItemHydrator extends AbstractHydrator
{
    use ValidatorTrait;

    /**
     * {@inheritdoc}
     */
    protected function validateClientGeneratedId(
        string $clientGeneratedId,
        JsonApiRequestInterface $request,
        ExceptionFactoryInterface $exceptionFactory
    ): void {
        if (!empty($clientGeneratedId)) {
            throw $exceptionFactory->createClientGeneratedIdNotSupportedException($request, $clientGeneratedId);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function generateId(): string
    {
        return '';
    }

    /**
     * {@inheritdoc}
     */
    protected function getAcceptedTypes(): array
    {
        return ['shopping_list_items'];
    }

    /**
     * {@inheritdoc}
     */
    protected function getAttributeHydrator($shoppingListItem): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    protected function validateRequest(JsonApiRequestInterface $request): void
    {
        $this->validateFields($this->objectManager->getClassMetadata(ShoppingListItem::class), $request);
    }

    /**
     * {@inheritdoc}
     */
    protected function setId($shoppingListItem, string $id): void
    {
        if ($id && (string) $shoppingListItem->getId() !== $id) {
            throw new NotFoundHttpException('both ids in url & body must be the same');
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function getRelationshipHydrator($shoppingListItem): array
    {
        return [
            'groceryItem' => function (ShoppingListItem $shoppingListItem, ToOneRelationship $groceryItem, $data, $relationshipName) {
                $this->validateRelationType($groceryItem, ['grocery_items']);

                $association = null;
                $identifier = $groceryItem->getResourceIdentifier();
                if ($identifier) {
                    $association = $this->objectManager->getRepository('App\Entity\GroceryItem')
                        ->find($identifier->getId());

                    if (is_null($association)) {
                        throw new InvalidRelationshipValueException($relationshipName, [$identifier->getId()]);
                    }
                }

                $shoppingListItem->setGroceryItem($association);
            },
        ];
    }
}
