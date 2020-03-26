<?php

namespace App\JsonApi\Hydrator\Configuration;

use App\Entity\Configuration;

/**
 * Update Configuration Hydrator.
 */
class UpdateConfigurationHydrator extends AbstractConfigurationHydrator
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
