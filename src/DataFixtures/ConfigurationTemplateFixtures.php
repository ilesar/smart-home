<?php

namespace App\DataFixtures;

use App\Entity\ConfigurationTemplate;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ConfigurationTemplateFixtures extends BaseFixture implements DependentFixtureInterface
{

    public const TV_LIGHT_TEMPLATE = "tvlighttemplate";
    public const SOFA_LIGHT_TEMPLATE = "sofalighttemplate";

    protected function loadData(ObjectManager $manager)
    {
        $configurationTemplateItems = $this->getMany(ConfigurationTemplateItemFixtures::TV_LIGHT_TEMPLATE_ITEMS, 15);

        $this->createOne(
            ConfigurationTemplate::class,
            self::TV_LIGHT_TEMPLATE,
            function (ConfigurationTemplate $configurationTemplate) use ($configurationTemplateItems) {
                foreach ($configurationTemplateItems as $configurationTemplateItem) {
                    $configurationTemplate->addItem($configurationTemplateItem);
                }
                $configurationTemplate->setIsActive(true);
            }
        );

        $configurationTemplateItems = $this->getMany(ConfigurationTemplateItemFixtures::SOFA_LIGHT_TEMPLATE_ITEMS, 30);

        $this->createOne(
            ConfigurationTemplate::class,
            self::SOFA_LIGHT_TEMPLATE,
            function (ConfigurationTemplate $configurationTemplate) use ($configurationTemplateItems) {
                foreach ($configurationTemplateItems as $configurationTemplateItem) {
                    $configurationTemplate->addItem($configurationTemplateItem);
                }
                $configurationTemplate->setIsActive(true);
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
            ConfigurationTemplateItemFixtures::class,
        ];
    }
}
