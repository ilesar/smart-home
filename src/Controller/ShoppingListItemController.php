<?php

namespace App\Controller;

use App\Entity\ShoppingListItem;
use App\JsonApi\Document\ShoppingListItem\ShoppingListItemDocument;
use App\JsonApi\Document\ShoppingListItem\ShoppingListItemsDocument;
use App\JsonApi\Hydrator\ShoppingListItem\CreateShoppingListItemHydrator;
use App\JsonApi\Hydrator\ShoppingListItem\UpdateShoppingListItemHydrator;
use App\JsonApi\Transformer\ShoppingListItemResourceTransformer;
use App\Repository\ShoppingListItemRepository;
use Paknahad\JsonApiBundle\Controller\Controller;
use Paknahad\JsonApiBundle\Helper\ResourceCollection;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use WoohooLabs\Yin\JsonApi\Exception\DefaultExceptionFactory;

/**
 * @Route("/shopping/list/items")
 */
class ShoppingListItemController extends Controller
{
    /**
     * @Route("/", name="shopping_list_items_index", methods="GET")
     */
    public function index(ShoppingListItemRepository $shoppingListItemRepository, ResourceCollection $resourceCollection, ShoppingListItemResourceTransformer $shoppingListItemResourceTransformer): ResponseInterface
    {
        $resourceCollection->setRepository($shoppingListItemRepository);

        $resourceCollection->handleIndexRequest();

        return $this->jsonApi()->respond()->ok(
            new ShoppingListItemsDocument($shoppingListItemResourceTransformer),
            $resourceCollection
        );
    }

    /**
     * @Route("/", name="shopping_list_items_new", methods="POST")
     */
    public function new(ValidatorInterface $validator, DefaultExceptionFactory $exceptionFactory, ShoppingListItemResourceTransformer $shoppingListItemResourceTransformer): ResponseInterface
    {
        $entityManager = $this->getDoctrine()->getManager();

        $shoppingListItem = $this->jsonApi()->hydrate(new CreateShoppingListItemHydrator($entityManager, $exceptionFactory), new ShoppingListItem());

        /** @var ConstraintViolationList $errors */
        $errors = $validator->validate($shoppingListItem);
        if ($errors->count() > 0) {
            return $this->validationErrorResponse($errors);
        }

        $entityManager->persist($shoppingListItem);
        $entityManager->flush();

        return $this->jsonApi()->respond()->ok(
            new ShoppingListItemDocument($shoppingListItemResourceTransformer),
            $shoppingListItem
        );
    }

    /**
     * @Route("/{id}", name="shopping_list_items_show", methods="GET")
     */
    public function show(ShoppingListItem $shoppingListItem, ShoppingListItemResourceTransformer $shoppingListItemResourceTransformer): ResponseInterface
    {
        return $this->jsonApi()->respond()->ok(
            new ShoppingListItemDocument($shoppingListItemResourceTransformer),
            $shoppingListItem
        );
    }

    /**
     * @Route("/{id}", name="shopping_list_items_edit", methods="PATCH")
     */
    public function edit(ShoppingListItem $shoppingListItem, ValidatorInterface $validator, DefaultExceptionFactory $exceptionFactory, ShoppingListItemResourceTransformer $shoppingListItemResourceTransformer): ResponseInterface
    {
        $entityManager = $this->getDoctrine()->getManager();

        $shoppingListItem = $this->jsonApi()->hydrate(new UpdateShoppingListItemHydrator($entityManager, $exceptionFactory), $shoppingListItem);

        /** @var ConstraintViolationList $errors */
        $errors = $validator->validate($shoppingListItem);
        if ($errors->count() > 0) {
            return $this->validationErrorResponse($errors);
        }

        $entityManager->flush();

        return $this->jsonApi()->respond()->ok(
            new ShoppingListItemDocument($shoppingListItemResourceTransformer),
            $shoppingListItem
        );
    }

    /**
     * @Route("/{id}", name="shopping_list_items_delete", methods="DELETE")
     */
    public function delete(ShoppingListItem $shoppingListItem): ResponseInterface
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($shoppingListItem);
        $entityManager->flush();

        return $this->jsonApi()->respond()->genericSuccess(204);
    }
}
