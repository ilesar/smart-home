<?php

namespace App\DataFixtures;

use App\Entity\Configuration;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ConfigurationFixtures extends Fixture implements DependentFixtureInterface
{
    public const TV_LIGHT_CONFIGURATION = 'Tv Light Configuration';
    public const SOFA_LIGHT_CONFIGURATION = 'Sofa Light Configuration';

    public function load(ObjectManager $manager)
    {
        $device = $this->getReference(DeviceFixtures::TV_LIGHT);

        $configuration = new Configuration();
        $configuration->setDevice($device);
        $manager->persist($configuration);
        $this->setReference(self::TV_LIGHT_CONFIGURATION, $configuration);

        $device = $this->getReference(DeviceFixtures::SOFA_LIGHT);
        $configuration = new Configuration();
        $configuration->setDevice($device);
        $manager->persist($configuration);
        $this->setReference(self::SOFA_LIGHT_CONFIGURATION, $configuration);


        $manager->flush();


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
