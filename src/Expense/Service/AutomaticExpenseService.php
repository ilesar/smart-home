<?php

namespace App\Expense\Service;

use App\Entity\Expense;
use App\Entity\RecurringPayment;
use App\Repository\RecurringPaymentRepository;
use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;

class AutomaticExpenseService
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var RecurringPaymentRepository
     */
    private $recurringPaymentRepository;

    /**
     * @var RecurringPaymentRepository
     */
    private $expenseRepository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->recurringPaymentRepository = $this->entityManager->getRepository(RecurringPayment::class);
        $this->expenseRepository = $this->entityManager->getRepository(Expense::class);
    }

    public function updateExpenses()
    {
        $recurringPayments = $this->recurringPaymentRepository->findAll();

        foreach ($recurringPayments as $recurringPayment) {
            $this->createExpenseFromRecurringPayment($recurringPayment);
        }
    }

    private function createExpenseFromRecurringPayment(RecurringPayment $recurringPayment)
    {
        $expense = $this->expenseRepository->findOneBy([
            'recurringPayment' => $recurringPayment,
            'isResolved' => false,
        ]);

        if (null !== $expense) {
            return;
        }

        $expense = new Expense();
        $expense->setRecurringPayment($recurringPayment);
        $expense->setDueDate($this->createRandomMomentInFuture());

        $this->entityManager->persist($expense);
        $this->entityManager->flush();
    }

    private function createRandomMomentInFuture(): DateTimeImmutable
    {
        $currentMoment = new DateTime();
        $currentTimestamp = $currentMoment->getTimestamp();

        $timestampLowerBound = $currentTimestamp + (20 * 60 * 60);
        $timestampHigherBound = $timestampLowerBound + (40 * 60 * 60 * 24);

        $randomTimestamp = mt_rand($timestampLowerBound, $timestampHigherBound);

        return (new DateTimeImmutable())->setTimestamp($randomTimestamp);
    }
}
