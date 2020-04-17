<?php

namespace App\JsonApi\Hydrator\ConfigurationTemplateItem;

use App\Entity\ConfigurationTemplateItem;

/**
 * Create ConfigurationTemplateItem Hydrator.
 */
class CreateConfigurationTemplateItemHydrator extends AbstractConfigurationTemplateItemHydrator
{
    /**
     * {@inheritdoc}
     */
    protected function getAttributeHydrator($configurationTemplateItem): array
    {
        return [
            'value' => function (ConfigurationTemplateItem $configurationTemplateItem, $attribute, $data, $attributeName) {
                $configurationTemplateItem->setValue($attribute);
            },
        ];
    }
}
