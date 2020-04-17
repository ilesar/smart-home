<?php

namespace App\DataFixtures;

use App\Entity\Configuration;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ConfigurationFixtures extends BaseFixture implements DependentFixtureInterface
{
    public const TV_LIGHT_CONFIGURATION = 'Tv Light Configuration';
    public const SOFA_LIGHT_CONFIGURATION = 'Sofa Light Configuration';

    /**
     * {@inheritdoc}
     */
    public function getDependencies()
    {
        return [
            ConfigurationItemFixtures::class,
        ];
    }

    protected function loadData(ObjectManager $manager)
    {
        $configurationItems = $this->getMany(ConfigurationItemFixtures::TV_LIGHT_CONFIGURATION_ITEMS, 15);

        $this->createOne(
            Configuration::class,
            self::TV_LIGHT_CONFIGURATION,
            function (Configuration $configuration) use ($configurationItems) {
                foreach ($configurationItems as $configurationItem) {
                    $configuration->addItem($configurationItem);
                }
            }
        );

        $configurationItems = $this->getMany(ConfigurationItemFixtures::SOFA_LIGHT_CONFIGURATION_ITEMS, 30);

        $this->createOne(
            Configuration::class,
            self::SOFA_LIGHT_CONFIGURATION,
            function (Configuration $configuration) use ($configurationItems) {
                foreach ($configurationItems as $configurationItem) {
                    $configuration->addItem($configurationItem);
                }
            }
        );

        $manager->flush();
    }
}
