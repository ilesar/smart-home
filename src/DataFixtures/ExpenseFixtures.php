<?php

namespace App\DataFixtures;

use App\Entity\Expense;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ExpenseFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $payment = $this->getReference(RecurringPaymentFixtures::RENT);

        $expense = new Expense();
        $expense->setDueDate(new DateTimeImmutable());
        $expense->setRecurringPayment($payment);

        $manager->persist($expense);

        $manager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function getDependencies()
    {
        return [
            RecurringPaymentFixtures::class,
        ];
    }
}
