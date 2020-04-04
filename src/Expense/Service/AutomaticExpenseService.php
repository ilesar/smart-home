<?php

namespace App\Expense\Service;

use App\Entity\Expense;
use App\Entity\RecurringPayment;
use App\Repository\RecurringPaymentRepository;
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
        $expense->setDueDate(new \DateTimeImmutable());

        $this->entityManager->persist($expense);
        $this->entityManager->flush();
    }
}
