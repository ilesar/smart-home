<?php

namespace App\JsonApi\Transformer;

use App\Entity\ConfigurationTemplateItem;
use WoohooLabs\Yin\JsonApi\Schema\Link\Link;
use WoohooLabs\Yin\JsonApi\Schema\Link\ResourceLinks;
use WoohooLabs\Yin\JsonApi\Schema\Relationship\ToOneRelationship;
use WoohooLabs\Yin\JsonApi\Schema\Resource\AbstractResource;

/**
 * ConfigurationTemplateItem Resource Transformer.
 */
class ConfigurationTemplateItemResourceTransformer extends AbstractResource
{
    /**
     * {@inheritdoc}
     */
    public function getType($configurationTemplateItem): string
    {
        return 'configuration_template_items';
    }

    /**
     * {@inheritdoc}
     */
    public function getId($configurationTemplateItem): string
    {
        return (string) $configurationTemplateItem->getId();
    }

    /**
     * {@inheritdoc}
     */
    public function getMeta($configurationTemplateItem): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getLinks($configurationTemplateItem): ?ResourceLinks
    {
        return ResourceLinks::createWithoutBaseUri()->setSelf(new Link('/configuration/template/items/'.$this->getId($configurationTemplateItem)));
    }

    /**
     * {@inheritdoc}
     */
    public function getAttributes($configurationTemplateItem): array
    {
        return [
            'value' => function (ConfigurationTemplateItem $configurationTemplateItem) {
                return $configurationTemplateItem->getValue();
            },
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultIncludedRelationships($configurationTemplateItem): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getRelationships($configurationTemplateItem): array
    {
        return [
            'configurationItem' => function (ConfigurationTemplateItem $configurationTemplateItem) {
                return ToOneRelationship::create()
                    ->setDataAsCallable(
                        function () use ($configurationTemplateItem) {
                            return $configurationTemplateItem->getConfigurationItem();
                        },
                        new ConfigurationItemResourceTransformer()
                    )
                    ->omitDataWhenNotIncluded();
            },
            'template' => function (ConfigurationTemplateItem $configurationTemplateItem) {
                return ToOneRelationship::create()
                    ->setDataAsCallable(
                        function () use ($configurationTemplateItem) {
                            return $configurationTemplateItem->getTemplate();
                        },
                        new ConfigurationTemplateResourceTransformer()
                    )
                    ->omitDataWhenNotIncluded();
            },
        ];
    }
}
