<?php

namespace App\JsonApi\Hydrator\ConfigurationTemplate;

use App\Entity\ConfigurationTemplate;

/**
 * Create ConfigurationTemplate Hydrator.
 */
class CreateConfigurationTemplateHydrator extends AbstractConfigurationTemplateHydrator
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
