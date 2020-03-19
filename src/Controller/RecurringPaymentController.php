<?php

namespace App\Controller;

use App\Entity\RecurringPayment;
use App\JsonApi\Document\RecurringPayment\RecurringPaymentDocument;
use App\JsonApi\Document\RecurringPayment\RecurringPaymentsDocument;
use App\JsonApi\Hydrator\RecurringPayment\CreateRecurringPaymentHydrator;
use App\JsonApi\Hydrator\RecurringPayment\UpdateRecurringPaymentHydrator;
use App\JsonApi\Transformer\RecurringPaymentResourceTransformer;
use App\Repository\RecurringPaymentRepository;
use Paknahad\JsonApiBundle\Controller\Controller;
use Paknahad\JsonApiBundle\Helper\ResourceCollection;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use WoohooLabs\Yin\JsonApi\Exception\DefaultExceptionFactory;

/**
 * @Route("/recurring/payments")
 */
class RecurringPaymentController extends Controller
{
    /**
     * @Route("/", name="recurring_payments_index", methods="GET")
     */
    public function index(RecurringPaymentRepository $recurringPaymentRepository, ResourceCollection $resourceCollection): ResponseInterface
    {
        $resourceCollection->setRepository($recurringPaymentRepository);

        $resourceCollection->handleIndexRequest();

        return $this->jsonApi()->respond()->ok(
            new RecurringPaymentsDocument(new RecurringPaymentResourceTransformer()),
            $resourceCollection
        );
    }

    /**
     * @Route("/", name="recurring_payments_new", methods="POST")
     */
    public function new(ValidatorInterface $validator, DefaultExceptionFactory $exceptionFactory): ResponseInterface
    {
        $entityManager = $this->getDoctrine()->getManager();

        $recurringPayment = $this->jsonApi()->hydrate(new CreateRecurringPaymentHydrator($entityManager, $exceptionFactory), new RecurringPayment());

        /** @var ConstraintViolationList $errors */
        $errors = $validator->validate($recurringPayment);
        if ($errors->count() > 0) {
            return $this->validationErrorResponse($errors);
        }

        $entityManager->persist($recurringPayment);
        $entityManager->flush();

        return $this->jsonApi()->respond()->ok(
            new RecurringPaymentDocument(new RecurringPaymentResourceTransformer()),
            $recurringPayment
        );
    }

    /**
     * @Route("/{id}", name="recurring_payments_show", methods="GET")
     */
    public function show(RecurringPayment $recurringPayment): ResponseInterface
    {
        return $this->jsonApi()->respond()->ok(
            new RecurringPaymentDocument(new RecurringPaymentResourceTransformer()),
            $recurringPayment
        );
    }

    /**
     * @Route("/{id}", name="recurring_payments_edit", methods="PATCH")
     */
    public function edit(RecurringPayment $recurringPayment, ValidatorInterface $validator, DefaultExceptionFactory $exceptionFactory): ResponseInterface
    {
        $entityManager = $this->getDoctrine()->getManager();

        $recurringPayment = $this->jsonApi()->hydrate(new UpdateRecurringPaymentHydrator($entityManager, $exceptionFactory), $recurringPayment);

        /** @var ConstraintViolationList $errors */
        $errors = $validator->validate($recurringPayment);
        if ($errors->count() > 0) {
            return $this->validationErrorResponse($errors);
        }

        $entityManager->flush();

        return $this->jsonApi()->respond()->ok(
            new RecurringPaymentDocument(new RecurringPaymentResourceTransformer()),
            $recurringPayment
        );
    }

    /**
     * @Route("/{id}", name="recurring_payments_delete", methods="DELETE")
     */
    public function delete(RecurringPayment $recurringPayment): ResponseInterface
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($recurringPayment);
        $entityManager->flush();

        return $this->jsonApi()->respond()->genericSuccess(204);
    }
}
