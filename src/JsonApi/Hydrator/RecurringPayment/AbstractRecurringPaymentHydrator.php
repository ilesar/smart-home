<?php

namespace App\JsonApi\Hydrator\RecurringPayment;

use App\Entity\RecurringPayment;
use Doctrine\ORM\Query\Expr;
use Paknahad\JsonApiBundle\Hydrator\AbstractHydrator;
use Paknahad\JsonApiBundle\Hydrator\ValidatorTrait;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface;
use WoohooLabs\Yin\JsonApi\Hydrator\Relationship\ToManyRelationship;
use WoohooLabs\Yin\JsonApi\Request\JsonApiRequestInterface;

/**
 * Abstract RecurringPayment Hydrator.
 */
abstract class AbstractRecurringPaymentHydrator extends AbstractHydrator
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
        return ['recurring_payments'];
    }

    /**
     * {@inheritdoc}
     */
    protected function getAttributeHydrator($recurringPayment): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    protected function validateRequest(JsonApiRequestInterface $request): void
    {
        $this->validateFields($this->objectManager->getClassMetadata(RecurringPayment::class), $request);
    }

    /**
     * {@inheritdoc}
     */
    protected function setId($recurringPayment, string $id): void
    {
        if ($id && (string) $recurringPayment->getId() !== $id) {
            throw new NotFoundHttpException('both ids in url & body must be the same');
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function getRelationshipHydrator($recurringPayment): array
    {
        return [
            'expenses' => function (RecurringPayment $recurringPayment, ToManyRelationship $expenses, $data, $relationshipName) {
                $this->validateRelationType($expenses, ['expenses']);

                if (count($expenses->getResourceIdentifierIds()) > 0) {
                    $association = $this->objectManager->getRepository('App\Entity\Expense')
                        ->createQueryBuilder('e')
                        ->where((new Expr())->in('e.id', $expenses->getResourceIdentifierIds()))
                        ->getQuery()
                        ->getResult();

                    $this->validateRelationValues($association, $expenses->getResourceIdentifierIds(), $relationshipName);
                } else {
                    $association = [];
                }

                if ($recurringPayment->getExpenses()->count() > 0) {
                    foreach ($recurringPayment->getExpenses() as $expense) {
                        $recurringPayment->removeExpense($expense);
                    }
                }

                foreach ($association as $expense) {
                    $recurringPayment->addExpense($expense);
                }
            },
        ];
    }
}
