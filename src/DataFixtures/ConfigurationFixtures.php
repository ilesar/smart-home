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
        /** @var ConfigurationTemplate $configurationTemplate2 */
        $configurationTemplate2 = $this->getOne(ConfigurationTemplateFixtures::TV_LIGHT_TEMPLATE_2);
        /** @var ConfigurationTemplate $configurationTemplate3 */
        $configurationTemplate3 = $this->getOne(ConfigurationTemplateFixtures::TV_LIGHT_TEMPLATE_3);

        $this->createOne(
            Configuration::class,
            self::TV_LIGHT_CONFIGURATION,
            function (Configuration $configuration) use ($configurationItems, $configurationTemplate, $configurationTemplate2, $configurationTemplate3) {
                foreach ($configurationItems as $configurationItem) {
                    $configuration->addItem($configurationItem);
                }
                $configuration->addTemplate($configurationTemplate);
                $configuration->addTemplate($configurationTemplate2);
                $configuration->addTemplate($configurationTemplate3);
            }
        );

        /** @var ConfigurationItem[] $configurationItems */
        $configurationItems = $this->getMany(ConfigurationItemFixtures::SOFA_LIGHT_CONFIGURATION_ITEMS, 30);

        /** @var ConfigurationTemplate $configurationTemplate */
        $configurationTemplate = $this->getOne(ConfigurationTemplateFixtures::SOFA_LIGHT_TEMPLATE);
        /** @var ConfigurationTemplate $configurationTemplate */
        $configurationTemplate2 = $this->getOne(ConfigurationTemplateFixtures::SOFA_LIGHT_TEMPLATE_2);

        $this->createOne(
            Configuration::class,
            self::SOFA_LIGHT_CONFIGURATION,
            function (Configuration $configuration) use ($configurationItems, $configurationTemplate, $configurationTemplate2) {
                foreach ($configurationItems as $configurationItem) {
                    $configuration->addItem($configurationItem);
                }
                $configuration->addTemplate($configurationTemplate);
                $configuration->addTemplate($configurationTemplate2);
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
