<?php

namespace App\DataFixtures;

use App\Entity\Configuration;
use App\Entity\ConfigurationItem;
use App\Entity\ConfigurationTemplate;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ConfigurationFixtures extends BaseFixture implements DependentFixtureInterface
{
    public const TV_LIGHT_CONFIGURATION = 'Tv Light Configuration';
    public const SOFA_LIGHT_CONFIGURATION = 'Sofa Light Configuration';

    protected function loadData(ObjectManager $manager)
    {
        /** @var ConfigurationItem[] $configurationItems */
        $configurationItems = $this->getMany(ConfigurationItemFixtures::TV_LIGHT_CONFIGURATION_ITEMS, 15);
        /** @var ConfigurationTemplate $configurationTemplate */
        $configurationTemplate = $this->getOne(ConfigurationTemplateFixtures::TV_LIGHT_TEMPLATE);

        $this->createOne(
            Configuration::class,
            self::TV_LIGHT_CONFIGURATION,
            function (Configuration $configuration) use ($configurationItems, $configurationTemplate) {
                foreach ($configurationItems as $configurationItem) {
                    $configuration->addItem($configurationItem);
                }
                $configuration->addTemplate($configurationTemplate);
            }
        );

        /** @var ConfigurationItem[] $configurationItems */
        $configurationItems = $this->getMany(ConfigurationItemFixtures::SOFA_LIGHT_CONFIGURATION_ITEMS, 30);
        /** @var ConfigurationTemplate $configurationTemplate */
        $configurationTemplate = $this->getOne(ConfigurationTemplateFixtures::SOFA_LIGHT_TEMPLATE);

        $this->createOne(
            Configuration::class,
            self::SOFA_LIGHT_CONFIGURATION,
            function (Configuration $configuration) use ($configurationItems, $configurationTemplate) {
                foreach ($configurationItems as $configurationItem) {
                    $configuration->addItem($configurationItem);
                }
                $configuration->addTemplate($configurationTemplate);
            }
        );

        $manager->flush();
    }


    /**
     * {@inheritdoc}
     */
    public function getDependencies()
    {
        return [
            ConfigurationItemFixtures::class,
            ConfigurationTemplateFixtures::class,
        ];
    }

}
