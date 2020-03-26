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
        ];
    }
}
