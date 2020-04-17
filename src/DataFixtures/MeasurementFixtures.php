<?php

namespace App\DataFixtures;

use App\Entity\Device;
use App\Entity\Measurement;
use App\Enum\MeasurementType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class MeasurementFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
//        /** @var Device $motionSensor */
//        $motionSensor = $this->getReference(DeviceFixtures::MOTION_SENSOR);
//
//        for ($i = 0; $i < 10; ++$i) {
//            $measurement = new Measurement();
//            $measurement->setDevice($motionSensor);
//            $measurement->setName($motionSensor->getName());
//            $measurement->setType(MeasurementType::BOOLEAN);
//            $measurement->setValue(1);
//
//            $manager->persist($measurement);
//        }
//
//        $manager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function getDependencies()
    {
        return [
            DeviceFixtures::class,
        ];
    }
}
