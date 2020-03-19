<?php

namespace App\JsonApi\Hydrator\Room;

use App\Entity\Room;

/**
 * Create Room Hydrator.
 */
class CreateRoomHydrator extends AbstractRoomHydrator
{
    /**
     * {@inheritdoc}
     */
    protected function getAttributeHydrator($room): array
    {
        return [
            'name' => function (Room $room, $attribute, $data, $attributeName) {
                $room->setName($attribute);
            },
            'createdAt' => function (Room $room, $attribute, $data, $attributeName) {
                $room->setCreatedAt(new \DateTime($attribute));
            },
            'updatedAt' => function (Room $room, $attribute, $data, $attributeName) {
                $room->setUpdatedAt(new \DateTime($attribute));
            },
        ];
    }
}
