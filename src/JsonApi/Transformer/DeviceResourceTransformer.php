<?php

namespace App\JsonApi\Transformer;

use App\Entity\Device;
use WoohooLabs\Yin\JsonApi\Schema\Link\Link;
use WoohooLabs\Yin\JsonApi\Schema\Link\ResourceLinks;
use WoohooLabs\Yin\JsonApi\Schema\Relationship\ToManyRelationship;
use WoohooLabs\Yin\JsonApi\Schema\Relationship\ToOneRelationship;
use WoohooLabs\Yin\JsonApi\Schema\Resource\AbstractResource;

/**
 * Device Resource Transformer.
 */
class DeviceResourceTransformer extends AbstractResource
{
    /**
     * {@inheritdoc}
     */
    public function getType($device): string
    {
        return 'devices';
    }

    /**
     * {@inheritdoc}
     */
    public function getId($device): string
    {
        return (string) $device->getId();
    }

    /**
     * {@inheritdoc}
     */
    public function getMeta($device): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getLinks($device): ?ResourceLinks
    {
        return ResourceLinks::createWithoutBaseUri()->setSelf(new Link('/devices/'.$this->getId($device)));
    }

    /**
     * {@inheritdoc}
     */
    public function getAttributes($device): array
    {
        return [
            'name' => function (Device $device) {
                return $device->getName();
            },
            'deviceType' => function (Device $device) {
                return $device->getDeviceType();
            },
            'deviceId' => function (Device $device) {
                return $device->getDeviceId();
            },
            'createdAt' => function (Device $device) {
                return $device->getCreatedAt()->format(DATE_ATOM);
            },
            'updatedAt' => function (Device $device) {
                return $device->getUpdatedAt()->format(DATE_ATOM);
            },
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultIncludedRelationships($device): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getRelationships($device): array
    {
        return [
            'room' => function (Device $device) {
                return ToOneRelationship::create()
                    ->setDataAsCallable(
                        function () use ($device) {
                            return $device->getRoom();
                        },
                        new RoomResourceTransformer()
                    )
                    ->omitDataWhenNotIncluded();
            },
            'measurements' => function (Device $device) {
                return ToManyRelationship::create()
                    ->setDataAsCallable(
                        function () use ($device) {
                            return $device->getMeasurements();
                        },
                        new MeasurementResourceTransformer()
                    )
                    ->omitDataWhenNotIncluded();
            },
            'configuration' => function (Device $device) {
                return ToOneRelationship::create()
                    ->setDataAsCallable(
                        function () use ($device) {
                            return $device->getConfiguration();
                        },
                        new ConfigurationResourceTransformer()
                    )
                    ->omitDataWhenNotIncluded();
            },
        ];
    }
}
