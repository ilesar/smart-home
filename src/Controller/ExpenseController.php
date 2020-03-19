<?php

namespace App\Controller;

use App\Entity\Expense;
use App\JsonApi\Document\Expense\ExpenseDocument;
use App\JsonApi\Document\Expense\ExpensesDocument;
use App\JsonApi\Hydrator\Expense\CreateExpenseHydrator;
use App\JsonApi\Hydrator\Expense\UpdateExpenseHydrator;
use App\JsonApi\Transformer\ExpenseResourceTransformer;
use App\Repository\ExpenseRepository;
use Paknahad\JsonApiBundle\Controller\Controller;
use Paknahad\JsonApiBundle\Helper\ResourceCollection;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use WoohooLabs\Yin\JsonApi\Exception\DefaultExceptionFactory;

/**
 * @Route("/expenses")
 */
class ExpenseController extends Controller
{
    /**
     * @Route("/", name="expenses_index", methods="GET")
     */
    public function index(ExpenseRepository $expenseRepository, ResourceCollection $resourceCollection): ResponseInterface
    {
        $resourceCollection->setRepository($expenseRepository);

        $resourceCollection->handleIndexRequest();

        return $this->jsonApi()->respond()->ok(
            new ExpensesDocument(new ExpenseResourceTransformer()),
            $resourceCollection
        );
    }

    /**
     * @Route("/", name="expenses_new", methods="POST")
     */
    public function new(ValidatorInterface $validator, DefaultExceptionFactory $exceptionFactory): ResponseInterface
    {
        $entityManager = $this->getDoctrine()->getManager();

        $expense = $this->jsonApi()->hydrate(new CreateExpenseHydrator($entityManager, $exceptionFactory), new Expense());

        /** @var ConstraintViolationList $errors */
        $errors = $validator->validate($expense);
        if ($errors->count() > 0) {
            return $this->validationErrorResponse($errors);
        }

        $entityManager->persist($expense);
        $entityManager->flush();

        return $this->jsonApi()->respond()->ok(
            new ExpenseDocument(new ExpenseResourceTransformer()),
            $expense
        );
    }

    /**
     * @Route("/{id}", name="expenses_show", methods="GET")
     */
    public function show(Expense $expense): ResponseInterface
    {
        return $this->jsonApi()->respond()->ok(
            new ExpenseDocument(new ExpenseResourceTransformer()),
            $expense
        );
    }

    /**
     * @Route("/{id}", name="expenses_edit", methods="PATCH")
     */
    public function edit(Expense $expense, ValidatorInterface $validator, DefaultExceptionFactory $exceptionFactory): ResponseInterface
    {
        $entityManager = $this->getDoctrine()->getManager();

        $expense = $this->jsonApi()->hydrate(new UpdateExpenseHydrator($entityManager, $exceptionFactory), $expense);

        /** @var ConstraintViolationList $errors */
        $errors = $validator->validate($expense);
        if ($errors->count() > 0) {
            return $this->validationErrorResponse($errors);
        }

        $entityManager->flush();

        return $this->jsonApi()->respond()->ok(
            new ExpenseDocument(new ExpenseResourceTransformer()),
            $expense
        );
    }

    /**
     * @Route("/{id}", name="expenses_delete", methods="DELETE")
     */
    public function delete(Expense $expense): ResponseInterface
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($expense);
        $entityManager->flush();

        return $this->jsonApi()->respond()->genericSuccess(204);
    }
}
