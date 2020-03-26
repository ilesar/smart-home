<?php

namespace App\JsonApi\Transformer;

use App\Entity\ConfigurationItem;
use WoohooLabs\Yin\JsonApi\Schema\Link\Link;
use WoohooLabs\Yin\JsonApi\Schema\Link\ResourceLinks;
use WoohooLabs\Yin\JsonApi\Schema\Relationship\ToOneRelationship;
use WoohooLabs\Yin\JsonApi\Schema\Resource\AbstractResource;

/**
 * ConfigurationItem Resource Transformer.
 */
class ConfigurationItemResourceTransformer extends AbstractResource
{
    /**
     * {@inheritdoc}
     */
    public function getType($configurationItem): string
    {
        return 'configuration_items';
    }

    /**
     * {@inheritdoc}
     */
    public function getId($configurationItem): string
    {
        return (string) $configurationItem->getId();
    }

    /**
     * {@inheritdoc}
     */
    public function getMeta($configurationItem): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getLinks($configurationItem): ?ResourceLinks
    {
        return ResourceLinks::createWithoutBaseUri()->setSelf(new Link('/configuration/items/'.$this->getId($configurationItem)));
    }

    /**
     * {@inheritdoc}
     */
    public function getAttributes($configurationItem): array
    {
        return [
            'name' => function (ConfigurationItem $configurationItem) {
                return $configurationItem->getName();
            },
            'description' => function (ConfigurationItem $configurationItem) {
                return $configurationItem->getDescription();
            },
            'inputType' => function (ConfigurationItem $configurationItem) {
                return $configurationItem->getInputType();
            },
            'outputFormat' => function (ConfigurationItem $configurationItem) {
                return $configurationItem->getOutputFormat();
            },
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultIncludedRelationships($configurationItem): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getRelationships($configurationItem): array
    {
        return [
            'configuration' => function (ConfigurationItem $configurationItem) {
                return ToOneRelationship::create()
                    ->setDataAsCallable(
                        function () use ($configurationItem) {
                            return $configurationItem->getConfiguration();
                        },
                        new ConfigurationResourceTransformer()
                    )
                    ->omitDataWhenNotIncluded();
            },
        ];
    }
}
