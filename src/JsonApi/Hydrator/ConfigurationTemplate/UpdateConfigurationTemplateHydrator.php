<?php

namespace App\JsonApi\Hydrator\ConfigurationTemplate;

use App\Entity\ConfigurationTemplate;

/**
 * Update ConfigurationTemplate Hydrator.
 */
class UpdateConfigurationTemplateHydrator extends AbstractConfigurationTemplateHydrator
{
    /**
     * {@inheritdoc}
     */
    protected function getAttributeHydrator($configurationTemplate): array
    {
        return [
            'isActive' => function (ConfigurationTemplate $configurationTemplate, $attribute, $data, $attributeName) {
                $configurationTemplate->setIsActive($attribute);
            },
        ];
    }
}
