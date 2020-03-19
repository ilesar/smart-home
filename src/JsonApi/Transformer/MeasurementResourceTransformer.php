<?php

namespace App\JsonApi\Transformer;

use App\Entity\Measurement;
use WoohooLabs\Yin\JsonApi\Schema\Link\Link;
use WoohooLabs\Yin\JsonApi\Schema\Link\ResourceLinks;
use WoohooLabs\Yin\JsonApi\Schema\Relationship\ToOneRelationship;
use WoohooLabs\Yin\JsonApi\Schema\Resource\AbstractResource;

/**
 * Measurement Resource Transformer.
 */
class MeasurementResourceTransformer extends AbstractResource
{
    /**
     * {@inheritdoc}
     */
    public function getType($measurement): string
    {
        return 'measurements';
    }

    /**
     * {@inheritdoc}
     */
    public function getId($measurement): string
    {
        return (string) $measurement->getId();
    }

    /**
     * {@inheritdoc}
     */
    public function getMeta($measurement): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getLinks($measurement): ?ResourceLinks
    {
        return ResourceLinks::createWithoutBaseUri()->setSelf(new Link('/measurements/'.$this->getId($measurement)));
    }

    /**
     * {@inheritdoc}
     */
    public function getAttributes($measurement): array
    {
        return [
            'name' => function (Measurement $measurement) {
                return $measurement->getName();
            },
            'value' => function (Measurement $measurement) {
                return $measurement->getValue();
            },
            'type' => function (Measurement $measurement) {
                return $measurement->getType();
            },
            'createdAt' => function (Measurement $measurement) {
                return $measurement->getCreatedAt()->format(DATE_ATOM);
            },
            'updatedAt' => function (Measurement $measurement) {
                return $measurement->getUpdatedAt()->format(DATE_ATOM);
            },
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultIncludedRelationships($measurement): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getRelationships($measurement): array
    {
        return [
            'device' => function (Measurement $measurement) {
                return ToOneRelationship::create()
                    ->setDataAsCallable(
                        function () use ($measurement) {
                            return $measurement->getDevice();
                        },
                        new DeviceResourceTransformer()
                    )
                    ->omitDataWhenNotIncluded();
            },
        ];
    }
}
