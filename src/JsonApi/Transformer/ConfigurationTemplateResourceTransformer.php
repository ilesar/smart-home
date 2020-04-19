<?php

namespace App\JsonApi\Transformer;

use App\Entity\ConfigurationTemplate;
use WoohooLabs\Yin\JsonApi\Schema\Link\Link;
use WoohooLabs\Yin\JsonApi\Schema\Link\ResourceLinks;
use WoohooLabs\Yin\JsonApi\Schema\Relationship\ToManyRelationship;
use WoohooLabs\Yin\JsonApi\Schema\Relationship\ToOneRelationship;
use WoohooLabs\Yin\JsonApi\Schema\Resource\AbstractResource;

/**
 * ConfigurationTemplate Resource Transformer.
 */
class ConfigurationTemplateResourceTransformer extends AbstractResource
{
    /**
     * {@inheritdoc}
     */
    public function getType($configurationTemplate): string
    {
        return 'configuration_templates';
    }

    /**
     * {@inheritdoc}
     */
    public function getId($configurationTemplate): string
    {
        return (string) $configurationTemplate->getId();
    }

    /**
     * {@inheritdoc}
     */
    public function getMeta($configurationTemplate): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getLinks($configurationTemplate): ?ResourceLinks
    {
        return ResourceLinks::createWithoutBaseUri()->setSelf(new Link('/configuration/templates/'.$this->getId($configurationTemplate)));
    }

    /**
     * {@inheritdoc}
     */
    public function getAttributes($configurationTemplate): array
    {
        return [
            'name' => function (ConfigurationTemplate $configurationTemplate) {
                return $configurationTemplate->getName();
            },
            'isActive' => function (ConfigurationTemplate $configurationTemplate) {
                return $configurationTemplate->getIsActive();
            },
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultIncludedRelationships($configurationTemplate): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getRelationships($configurationTemplate): array
    {
        return [
            'configuration' => function (ConfigurationTemplate $configurationTemplate) {
                return ToOneRelationship::create()
                    ->setDataAsCallable(
                        function () use ($configurationTemplate) {
                            return $configurationTemplate->getConfiguration();
                        },
                        new ConfigurationResourceTransformer()
                    )
                    ->omitDataWhenNotIncluded();
            },
            'items' => function (ConfigurationTemplate $configurationTemplate) {
                return ToManyRelationship::create()
                    ->setDataAsCallable(
                        function () use ($configurationTemplate) {
                            return $configurationTemplate->getItems();
                        },
                        new ConfigurationTemplateItemResourceTransformer()
                    )
                    ->omitDataWhenNotIncluded();
            },
        ];
    }
}
