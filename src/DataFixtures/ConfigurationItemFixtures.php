<?php

namespace App\DataFixtures;

use App\Entity\ConfigurationItem;
use App\Enum\ConfigurationItemType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ConfigurationItemFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $configuration = $this->getReference(ConfigurationFixtures::TV_LIGHT_CONFIGURATION);

        for ($i = 0; $i < 15; ++$i) {
            $configurationItem = new ConfigurationItem();
            $configurationItem->setName(sprintf('LED #%s color', $i + 1));
            $configurationItem->setDescription(sprintf('Kontrola boje za LED svijetlo na traci na poziciji %s', $i + 1));
            $configurationItem->setInputType(ConfigurationItemType::COLOR);
            $configurationItem->setOutputFormat('%s');
            $configurationItem->setConfiguration($configuration);
            $manager->persist($configurationItem);
        }

        $configuration = $this->getReference(ConfigurationFixtures::SOFA_LIGHT_CONFIGURATION);

        for ($i = 0; $i < 30; ++$i) {
            $configurationItem = new ConfigurationItem();
            $configurationItem->setName(sprintf('LED %s', $i + 1));
            $configurationItem->setDescription(sprintf('Kontrola boje za LED svijetlo na traci na poziciji %s', $i + 1));
            $configurationItem->setInputType(ConfigurationItemType::COLOR);
            $configurationItem->setOutputFormat('%s');
            $configurationItem->setConfiguration($configuration);
            $manager->persist($configurationItem);
        }

        $manager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function getDependencies()
    {
        return [
            ConfigurationFixtures::class,
        ];
    }
}
