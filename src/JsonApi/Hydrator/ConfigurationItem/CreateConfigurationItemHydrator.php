<?php

namespace App\JsonApi\Hydrator\ConfigurationItem;

use App\Entity\ConfigurationItem;

/**
 * Create ConfigurationItem Hydrator.
 */
class CreateConfigurationItemHydrator extends AbstractConfigurationItemHydrator
{
    /**
     * {@inheritdoc}
     */
    protected function getAttributeHydrator($configurationItem): array
    {
        return [
            'name' => function (ConfigurationItem $configurationItem, $attribute, $data, $attributeName) {
                $configurationItem->setName($attribute);
            },
            'description' => function (ConfigurationItem $configurationItem, $attribute, $data, $attributeName) {
                $configurationItem->setDescription($attribute);
            },
            'inputType' => function (ConfigurationItem $configurationItem, $attribute, $data, $attributeName) {
                $configurationItem->setInputType($attribute);
            },
            'outputFormat' => function (ConfigurationItem $configurationItem, $attribute, $data, $attributeName) {
                $configurationItem->setOutputFormat($attribute);
            },
        ];
    }
}
