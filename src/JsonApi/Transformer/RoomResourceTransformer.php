<?php

namespace App\JsonApi\Transformer;

use App\Entity\Room;
use WoohooLabs\Yin\JsonApi\Schema\Link\Link;
use WoohooLabs\Yin\JsonApi\Schema\Link\ResourceLinks;
use WoohooLabs\Yin\JsonApi\Schema\Relationship\ToManyRelationship;
use WoohooLabs\Yin\JsonApi\Schema\Resource\AbstractResource;

/**
 * Room Resource Transformer.
 */
class RoomResourceTransformer extends AbstractResource
{
    /**
     * {@inheritdoc}
     */
    public function getType($room): string
    {
        return 'rooms';
    }

    /**
     * {@inheritdoc}
     */
    public function getId($room): string
    {
        return (string) $room->getId();
    }

    /**
     * {@inheritdoc}
     */
    public function getMeta($room): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getLinks($room): ?ResourceLinks
    {
        return ResourceLinks::createWithoutBaseUri()->setSelf(new Link('/rooms/'.$this->getId($room)));
    }

    /**
     * {@inheritdoc}
     */
    public function getAttributes($room): array
    {
        return [
            'name' => function (Room $room) {
                return $room->getName();
            },
            'createdAt' => function (Room $room) {
                return $room->getCreatedAt()->format(DATE_ATOM);
            },
            'updatedAt' => function (Room $room) {
                return $room->getUpdatedAt()->format(DATE_ATOM);
            },
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultIncludedRelationships($room): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getRelationships($room): array
    {
        return [
            'devices' => function (Room $room) {
                return ToManyRelationship::create()
                    ->setDataAsCallable(
                        function () use ($room) {
                            return $room->getDevices();
                        },
                        new DeviceResourceTransformer()
                    )
                    ->omitDataWhenNotIncluded();
            },
        ];
    }
}
