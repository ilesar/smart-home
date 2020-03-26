<?php

namespace App\DataFixtures;

use App\Entity\Configuration;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ConfigurationFixtures extends Fixture implements DependentFixtureInterface
{
    public const TV_LIGHT_CONFIGURATION = 'Tv Light Configuration';

    public function load(ObjectManager $manager)
    {
        $device = $this->getReference(DeviceFixtures::TV_LIGHT);

        $configuration = new Configuration();
        $configuration->setDevice($device);

        $manager->persist($configuration);
        $manager->flush();

        $this->setReference(self::TV_LIGHT_CONFIGURATION, $configuration);
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
