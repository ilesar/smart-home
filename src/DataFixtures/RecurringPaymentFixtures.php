<?php

namespace App\DataFixtures;

use App\Entity\RecurringPayment;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class RecurringPaymentFixtures extends Fixture
{
    public const CAR = 'Auto - Leasing rata';
    public const RENT = 'Stanarina za sljedeći mjesec';
    public const UTILITY_BILLS = 'Režije za prošli mjesec';
    public const INTERNET = 'B Net';
    public const INSURANCE_IVAN = 'Dopunsko osiguranje - Ivan';
    public const INSURANCE_SABINA = 'Dopunsko osiguranje - Sabina';
    public const NETFLIX = 'Netflix';
    public const BANK_CHARGES_IVAN_PBZ = 'PBZ naknade - Ivan';
    public const BANK_CHARGES_SABINA_PBZ = 'PBZ naknade - Sabina';
    public const BANK_CHARGES_SABINA_ZABA = 'ZABA naknade - Sabina';

    public const CAR_REGISTRATION = 'Auto - Registracija';
    public const CAR_TECHNICAL_EXAMINATION = 'Auto - Tehnički pregled';
    public const CAR_SERVICE = 'Auto - obavezni servis';
    public const CAR_INSURANCE_BASIC = 'Auto - Osiguranje';
    public const CAR_INSURANCE_KASKO = 'Auto - Kasko';
    public const CAR_PARKING = 'Auto - Kasko';
    public const CAR_TIRE_STORAGE_WINTER = 'Auto - Skladištenje guma';
    public const CAR_TIRE_STORAGE_SUMMER = 'Auto - Skladištenje guma';
    public const CAR_TIRE_CHANGE_WINTER = 'Auto - Promjena guma';
    public const CAR_TIRE_CHANGE_SUMMER = 'Auto - Promjena guma';
    const CONTACT_LENSES_SABINA_WINTER = 'Kontaktne leće - Sabina';
    const CONTACT_LENSES_SABINA_SUMMER = 'Kontaktne leće - Sabina';

    private $recurringPayments = [];

    public function load(ObjectManager $manager)
    {
        $this->loadData();

        foreach ($this->recurringPayments as $paymentName => $paymentData) {
            $payment = new RecurringPayment();
            $payment->setName($paymentName);
            $payment->setPrice($paymentData['price']);
            $payment->setPeriod($paymentData['period']);
            $payment->setActivationTime($paymentData['activation_time']);
            $payment->setPaymentTag($paymentData['icon']);

            $manager->persist($payment);
            $this->addReference($paymentName, $payment);
        }

        $manager->flush();
    }

    private function loadData()
    {
        $this->loadMonthlyPayments();
        $this->loadYearlyPayments();
    }

    private function loadMonthlyPayments()
    {
        $this->recurringPayments = array_merge($this->recurringPayments, [
            self::CAR => [
                'price' => 1600.0,
                'period' => 'month',
                'activation_time' => $this->createMonthlyMoment(5),
                'icon' => 'car',
            ],
            self::RENT => [
                'price' => 3000.0,
                'period' => 'month',
                'activation_time' => $this->createMonthlyMoment(25),
                'icon' => 'home',
            ],
            self::UTILITY_BILLS => [
                'price' => 800.0,
                'period' => 'month',
                'activation_time' => $this->createMonthlyMoment(20),
                'icon' => 'home',
            ],
            self::INTERNET => [
                'price' => 179.0,
                'period' => 'month',
                'activation_time' => $this->createMonthlyMoment(13),
                'icon' => 'home',
            ],
            self::INSURANCE_IVAN => [
                'price' => 69.0,
                'period' => 'month',
                'activation_time' => $this->createMonthlyMoment(16),
                'isAutomated' => true,
                'icon' => 'bank',
            ],
            self::INSURANCE_SABINA => [
                'price' => 67.0,
                'period' => 'month',
                'activation_time' => $this->createMonthlyMoment(16),
                'isAutomated' => true,
                'icon' => 'medicine-box',
            ],
            self::NETFLIX => [
                'price' => 61.0,
                'period' => 'month',
                'activation_time' => $this->createMonthlyMoment(11),
                'isAutomated' => true,
                'icon' => 'medicine-box',
            ],
            self::BANK_CHARGES_IVAN_PBZ => [
                'price' => 24.65,
                'period' => 'month',
                'activation_time' => $this->createMonthlyMoment(31),
                'isAutomated' => true,
                'icon' => 'bank',
            ],
            self::BANK_CHARGES_SABINA_PBZ => [
                'price' => 24.65,
                'period' => 'month',
                'activation_time' => $this->createMonthlyMoment(31),
                'isAutomated' => true,
                'icon' => 'bank',
            ],
            self::BANK_CHARGES_SABINA_ZABA => [
                'price' => 30,
                'period' => 'month',
                'activation_time' => $this->createMonthlyMoment(18),
                'isAutomated' => true,
                'icon' => 'bank',
            ],
        ]);
    }

    private function loadYearlyPayments()
    {
        $this->recurringPayments = array_merge($this->recurringPayments, [
            self::CAR_REGISTRATION => [
                'price' => 786.25,
                'period' => 'year',
                'activation_time' => $this->createYearlyMoment(15, 8),
                'icon' => 'car',
            ],
            self::CAR_TECHNICAL_EXAMINATION => [
                'price' => 0.0,
                'period' => 'year',
                'activation_time' => $this->createYearlyMoment(15, 8),
                'icon' => 'car',
            ],
            self::CAR_SERVICE => [
                'price' => 1234.0,
                'period' => 'year',
                'activation_time' => $this->createYearlyMoment(15, 8),
                'icon' => 'car',
            ],
            self::CAR_INSURANCE_BASIC => [
                'price' => 1323.28,
                'period' => 'year',
                'activation_time' => $this->createYearlyMoment(15, 8),
                'icon' => 'car',
            ],
            self::CAR_INSURANCE_KASKO => [
                'price' => 2595.22,
                'period' => 'year',
                'activation_time' => $this->createYearlyMoment(15, 8),
                'icon' => 'car',
            ],
            self::CAR_PARKING => [
                'price' => 480.0,
                'period' => 'year',
                'activation_time' => $this->createYearlyMoment(2, 10),
                'icon' => 'car',
            ],
            self::CAR_TIRE_STORAGE_WINTER => [
                'price' => 200.0,
                'period' => 'year',
                'activation_time' => $this->createYearlyMoment(15, 11),
                'icon' => 'car',
            ],
            self::CAR_TIRE_STORAGE_SUMMER => [
                'price' => 200.0,
                'period' => 'year',
                'activation_time' => $this->createYearlyMoment(15, 11),
                'icon' => 'car',
            ],
            self::CAR_TIRE_CHANGE_WINTER => [
                'price' => 276.0,
                'period' => 'year',
                'activation_time' => $this->createYearlyMoment(15, 4),
                'icon' => 'car',
            ],
            self::CAR_TIRE_CHANGE_SUMMER => [
                'price' => 276.0,
                'period' => 'year',
                'activation_time' => $this->createYearlyMoment(15, 4),
                'icon' => 'car',
            ],
            self::CONTACT_LENSES_SABINA_WINTER => [
                'price' => 350.0,
                'period' => 'year',
                'activation_time' => $this->createYearlyMoment(15, 12),
                'icon' => 'eye',
            ],
            self::CONTACT_LENSES_SABINA_SUMMER => [
                'price' => 350.0,
                'period' => 'year',
                'activation_time' => $this->createYearlyMoment(15, 6),
                'icon' => 'eye',
            ],
        ]);
    }

    private function createMonthlyMoment(int $day): DateTime
    {
        return new DateTime(vsprintf('2019-01-%s', [
            (string) ($day > 9 ? $day : '0'.$day),
        ]));
    }

    private function createYearlyMoment(int $day, int $month): DateTime
    {
        return new DateTime(vsprintf('2019-%s-%s', [
            (string) ($month > 9 ? $month : '0'.$month),
            (string) ($day > 9 ? $day : '0'.$day),
        ]));
    }
}
