<?php

namespace App\JsonApi\Hydrator\Configuration;

use App\Entity\Configuration;

/**
 * Create Configuration Hydrator.
 */
class CreateConfigurationHydrator extends AbstractConfigurationHydrator
{
    /**
     * {@inheritdoc}
     */
    protected function getAttributeHydrator($configuration): array
    {
        return [
        ];
    }
}
