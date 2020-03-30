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
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
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
     * @Route("/{id}", name="expenses_show", methods="GET")
     */
    public function show(Expense $expense): ResponseInterface
    {
        return $this->jsonApi()->respond()->ok(
            new ExpenseDocument(new ExpenseResourceTransformer()),
            $expense
        );
    }
}
