<?php

namespace App\DataFixtures;

use App\Entity\ConfigurationItem;
use App\Enum\ConfigurationItemType;
use Doctrine\Persistence\ObjectManager;

class ConfigurationItemFixtures extends BaseFixture
{
    public const TV_LIGHT_CONFIGURATION_ITEMS = 'tvligthitems';
    public const SOFA_LIGHT_CONFIGURATION_ITEMS = 'sofalightitems';

    protected function loadData(ObjectManager $manager)
    {

        $this->createMany(ConfigurationItem::class, 15, self::TV_LIGHT_CONFIGURATION_ITEMS, function (ConfigurationItem $configurationItem, $iterator) {
            $configurationItem->setName(sprintf('LED #%s color', $iterator + 1));
            $configurationItem->setDescription(sprintf('Kontrola boje za LED svijetlo na traci na poziciji %s', $iterator + 1));
            $configurationItem->setInputType(ConfigurationItemType::COLOR);
            $configurationItem->setDefaultValue('255');
            $configurationItem->setOutputFormat('%s');
        });

        $this->createMany(ConfigurationItem::class, 30, self::SOFA_LIGHT_CONFIGURATION_ITEMS, function (ConfigurationItem $configurationItem, $iterator) {
            $configurationItem->setName(sprintf('LED %s', $iterator + 1));
            $configurationItem->setDescription(sprintf('Kontrola boje za LED svijetlo na traci na poziciji %s', $iterator + 1));
            $configurationItem->setInputType(ConfigurationItemType::COLOR);
            $configurationItem->setDefaultValue('255');
            $configurationItem->setOutputFormat('%s');
        });

        $manager->flush();
    }
}
