<?php

namespace App\JsonApi\Hydrator\GroceryItem;

use App\Entity\GroceryItem;
use Doctrine\ORM\Query\Expr;
use Paknahad\JsonApiBundle\Exception\InvalidRelationshipValueException;
use Paknahad\JsonApiBundle\Hydrator\AbstractHydrator;
use Paknahad\JsonApiBundle\Hydrator\ValidatorTrait;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface;
use WoohooLabs\Yin\JsonApi\Hydrator\Relationship\ToManyRelationship;
use WoohooLabs\Yin\JsonApi\Hydrator\Relationship\ToOneRelationship;
use WoohooLabs\Yin\JsonApi\Request\JsonApiRequestInterface;

/**
 * Abstract GroceryItem Hydrator.
 */
abstract class AbstractGroceryItemHydrator extends AbstractHydrator
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
        return ['grocery_items'];
    }

    /**
     * {@inheritdoc}
     */
    protected function getAttributeHydrator($groceryItem): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    protected function validateRequest(JsonApiRequestInterface $request): void
    {
        $this->validateFields($this->objectManager->getClassMetadata(GroceryItem::class), $request);
    }

    /**
     * {@inheritdoc}
     */
    protected function setId($groceryItem, string $id): void
    {
        if ($id && (string) $groceryItem->getId() !== $id) {
            throw new NotFoundHttpException('both ids in url & body must be the same');
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function getRelationshipHydrator($groceryItem): array
    {
        return [
            'shoppingListItems' => function (GroceryItem $groceryItem, ToManyRelationship $shoppingListItems, $data, $relationshipName) {
                $this->validateRelationType($shoppingListItems, ['shopping_list_items']);

                if (count($shoppingListItems->getResourceIdentifierIds()) > 0) {
                    $association = $this->objectManager->getRepository('App\Entity\ShoppingListItem')
                        ->createQueryBuilder('s')
                        ->where((new Expr())->in('s.id', $shoppingListItems->getResourceIdentifierIds()))
                        ->getQuery()
                        ->getResult();

                    $this->validateRelationValues($association, $shoppingListItems->getResourceIdentifierIds(), $relationshipName);
                } else {
                    $association = [];
                }

                if ($groceryItem->getShoppingListItems()->count() > 0) {
                    foreach ($groceryItem->getShoppingListItems() as $shoppingListItem) {
                        $groceryItem->removeShoppingListItem($shoppingListItem);
                    }
                }

                foreach ($association as $shoppingListItem) {
                    $groceryItem->addShoppingListItem($shoppingListItem);
                }
            },
            'image' => function (GroceryItem $groceryItem, ToOneRelationship $image, $data, $relationshipName) {
                $this->validateRelationType($image, ['images']);

                $association = null;
                $identifier = $image->getResourceIdentifier();
                if ($identifier) {
                    $association = $this->objectManager->getRepository('App\Entity\Image')
                        ->find($identifier->getId());

                    if (is_null($association)) {
                        throw new InvalidRelationshipValueException($relationshipName, [$identifier->getId()]);
                    }
                }

                $groceryItem->setImage($association);
            },
        ];
    }
}
