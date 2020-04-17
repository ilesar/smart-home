<?php

namespace App\DataFixtures;

use App\Entity\ConfigurationItem;
use App\Entity\ConfigurationTemplateItem;
use Doctrine\Persistence\ObjectManager;

class ConfigurationTemplateItemFixtures extends BaseFixture
{
    public const TV_LIGHT_TEMPLATE_ITEMS = 'tvlighttemplateitem';
    public const SOFA_LIGHT_TEMPLATE_ITEMS = 'sofalighttemplateitem';

    protected function loadData(ObjectManager $manager)
    {
        /** @var ConfigurationItem[] $configurationItems */
        $configurationItems = $this->getMany(ConfigurationItemFixtures::TV_LIGHT_CONFIGURATION_ITEMS, 15);

        $this->createMany(
            ConfigurationTemplateItem::class,
            15,
            self::TV_LIGHT_TEMPLATE_ITEMS,
            function (ConfigurationTemplateItem $configurationTemplateItem, $iterator) use ($configurationItems) {
                $configurationTemplateItem->setValue($configurationItems[$iterator]->getDefaultValue());
                $configurationTemplateItem->setConfigurationItem($configurationItems[$iterator]);
            }
        );

        /** @var ConfigurationItem[] $configurationItems */
        $configurationItems = $this->getMany(ConfigurationItemFixtures::SOFA_LIGHT_CONFIGURATION_ITEMS, 30);

        $this->createMany(
            ConfigurationTemplateItem::class,
            30,
            self::SOFA_LIGHT_TEMPLATE_ITEMS,
            function (ConfigurationTemplateItem $configurationTemplateItem, $iterator) use ($configurationItems) {
                $configurationTemplateItem->setValue($configurationItems[$iterator]->getDefaultValue());
                $configurationTemplateItem->setConfigurationItem($configurationItems[$iterator]);
            }
        );


        $manager->flush();
    }
}
