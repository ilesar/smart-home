<?php

namespace App\DataFixtures;

use App\Entity\Room;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class RoomFixtures extends Fixture
{
    public const LIVING_ROOM = 'Dnevna soba';
    public const BEDROOM = 'Spavaća soba';
    public const KITCHEN = 'Kuhinja';
    public const HALLWAY = 'Hodnik';
    public const BALCONY = 'Balkon';
    public const BATHROOM = 'Kupaona';

    private const ROOM_NAMES = [
        self::LIVING_ROOM,
        self::BEDROOM,
        self::KITCHEN,
        self::HALLWAY,
        self::BALCONY,
        self::BATHROOM,
    ];

    public function load(ObjectManager $manager)
    {
        foreach (self::ROOM_NAMES as $roomName) {
            $room = new Room();
            $room->setName($roomName);

            $manager->persist($room);
            $this->addReference($roomName, $room);
        }

        $manager->flush();
    }
}
