<?php

namespace App\JsonApi\Transformer;

use App\Entity\Configuration;
use WoohooLabs\Yin\JsonApi\Schema\Link\Link;
use WoohooLabs\Yin\JsonApi\Schema\Link\ResourceLinks;
use WoohooLabs\Yin\JsonApi\Schema\Relationship\ToManyRelationship;
use WoohooLabs\Yin\JsonApi\Schema\Relationship\ToOneRelationship;
use WoohooLabs\Yin\JsonApi\Schema\Resource\AbstractResource;

/**
 * Configuration Resource Transformer.
 */
class ConfigurationResourceTransformer extends AbstractResource
{
    /**
     * {@inheritdoc}
     */
    public function getType($configuration): string
    {
        return 'configurations';
    }

    /**
     * {@inheritdoc}
     */
    public function getId($configuration): string
    {
        return (string) $configuration->getId();
    }

    /**
     * {@inheritdoc}
     */
    public function getMeta($configuration): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getLinks($configuration): ?ResourceLinks
    {
        return ResourceLinks::createWithoutBaseUri()->setSelf(new Link('/configurations/'.$this->getId($configuration)));
    }

    /**
     * {@inheritdoc}
     */
    public function getAttributes($configuration): array
    {
        return [
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultIncludedRelationships($configuration): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getRelationships($configuration): array
    {
        return [
            'items' => function (Configuration $configuration) {
                return ToManyRelationship::create()
                    ->setDataAsCallable(
                        function () use ($configuration) {
                            return $configuration->getItems();
                        },
                        new ConfigurationItemResourceTransformer()
                    )
                    ->omitDataWhenNotIncluded();
            },
            'templates' => function (Configuration $configuration) {
                return ToManyRelationship::create()
                    ->setDataAsCallable(
                        function () use ($configuration) {
                            return $configuration->getTemplates();
                        },
                        new ConfigurationTemplateResourceTransformer()
                    )
                    ->omitDataWhenNotIncluded();
            },
            'device' => function (Configuration $configuration) {
                return ToOneRelationship::create()
                    ->setDataAsCallable(
                        function () use ($configuration) {
                            return $configuration->getDevice();
                        },
                        new DeviceResourceTransformer()
                    )
                    ->omitDataWhenNotIncluded();
            },
        ];
    }
}
