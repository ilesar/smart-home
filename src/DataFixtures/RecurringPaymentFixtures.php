<?php

namespace App\DataFixtures;

use App\Entity\RecurringPayment;
use Doctrine\Bundle\FixturesBundle\Fixture;

class RecurringPaymentFixtures extends Fixture
{
    public const CAR = 'Auto';
    public const RENT = 'Stanarina';
    public const UTILITY_BILLS = 'Režije';
    public const INTERNET = 'B Net';

    private const RECURRING_PAYMENTS = [
        self::CAR => 1600,
        self::RENT => 3000,
        self::UTILITY_BILLS => 0,
        self::INTERNET => 180,
    ];

    public function load(\Doctrine\Persistence\ObjectManager $manager)
    {
        foreach (self::RECURRING_PAYMENTS as $paymentName => $paymentAmount) {
            $payment = new RecurringPayment();
            $payment->setName($paymentName);
            $payment->setPrice($paymentAmount);

            $manager->persist($payment);
            $this->addReference($paymentName, $payment);
        }

        $manager->flush();
    }
}
