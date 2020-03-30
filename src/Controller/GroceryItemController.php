<?php

namespace App\Controller;

use App\Entity\GroceryItem;
use App\JsonApi\Document\GroceryItem\GroceryItemDocument;
use App\JsonApi\Document\GroceryItem\GroceryItemsDocument;
use App\JsonApi\Hydrator\GroceryItem\CreateGroceryItemHydrator;
use App\JsonApi\Hydrator\GroceryItem\UpdateGroceryItemHydrator;
use App\JsonApi\Transformer\GroceryItemResourceTransformer;
use App\Repository\GroceryItemRepository;
use Paknahad\JsonApiBundle\Controller\Controller;
use Paknahad\JsonApiBundle\Helper\ResourceCollection;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use WoohooLabs\Yin\JsonApi\Exception\DefaultExceptionFactory;

/**
 * @Route("/grocery/items")
 */
class GroceryItemController extends Controller
{
    /**
     * @Route("/", name="grocery_items_index", methods="GET")
     */
    public function index(GroceryItemRepository $groceryItemRepository, ResourceCollection $resourceCollection): ResponseInterface
    {
        $resourceCollection->setRepository($groceryItemRepository);

        $resourceCollection->handleIndexRequest();

        return $this->jsonApi()->respond()->ok(
            new GroceryItemsDocument(new GroceryItemResourceTransformer()),
            $resourceCollection
        );
    }

    /**
     * @Route("/", name="grocery_items_new", methods="POST")
     */
    public function new(ValidatorInterface $validator, DefaultExceptionFactory $exceptionFactory): ResponseInterface
    {
        $entityManager = $this->getDoctrine()->getManager();

        $groceryItem = $this->jsonApi()->hydrate(new CreateGroceryItemHydrator($entityManager, $exceptionFactory), new GroceryItem());

        /** @var ConstraintViolationList $errors */
        $errors = $validator->validate($groceryItem);
        if ($errors->count() > 0) {
            return $this->validationErrorResponse($errors);
        }

        $entityManager->persist($groceryItem);
        $entityManager->flush();

        return $this->jsonApi()->respond()->ok(
            new GroceryItemDocument(new GroceryItemResourceTransformer()),
            $groceryItem
        );
    }

    /**
     * @Route("/{id}", name="grocery_items_show", methods="GET")
     */
    public function show(GroceryItem $groceryItem): ResponseInterface
    {
        return $this->jsonApi()->respond()->ok(
            new GroceryItemDocument(new GroceryItemResourceTransformer()),
            $groceryItem
        );
    }

    /**
     * @Route("/{id}", name="grocery_items_edit", methods="PATCH")
     */
    public function edit(GroceryItem $groceryItem, ValidatorInterface $validator, DefaultExceptionFactory $exceptionFactory): ResponseInterface
    {
        $entityManager = $this->getDoctrine()->getManager();

        $groceryItem = $this->jsonApi()->hydrate(new UpdateGroceryItemHydrator($entityManager, $exceptionFactory), $groceryItem);

        /** @var ConstraintViolationList $errors */
        $errors = $validator->validate($groceryItem);
        if ($errors->count() > 0) {
            return $this->validationErrorResponse($errors);
        }

        $entityManager->flush();

        return $this->jsonApi()->respond()->ok(
            new GroceryItemDocument(new GroceryItemResourceTransformer()),
            $groceryItem
        );
    }

    /**
     * @Route("/{id}", name="grocery_items_delete", methods="DELETE")
     */
    public function delete(GroceryItem $groceryItem): ResponseInterface
    {
        $entityManager = $this->getDoctrine()->getManager();
        $groceryItem->delete();
        $entityManager->flush();

        return $this->jsonApi()->respond()->genericSuccess(204);
    }
}
