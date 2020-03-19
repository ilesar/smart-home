<?php

namespace App\DataFixtures;

use App\Entity\Device;
use App\Entity\Room;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class DeviceFixtures extends Fixture implements DependentFixtureInterface
{
    public const TV = 'TV';
    public const LAMP = 'Xiaomi lamp';
    public const TEMP_SENSOR = 'Temperature sensor';
    public const TV_LIGHT = 'Tv light';
    public const MOTION_SENSOR = 'Motion sensor';

    private const DEVICE_NAMES = [
        self::TV => RoomFixtures::LIVING_ROOM,
        self::LAMP => RoomFixtures::BEDROOM,
        self::TEMP_SENSOR => RoomFixtures::LIVING_ROOM,
        self::TV_LIGHT => RoomFixtures::LIVING_ROOM,
        self::MOTION_SENSOR => RoomFixtures::LIVING_ROOM,
    ];

    public function load(ObjectManager $manager)
    {
        foreach (self::DEVICE_NAMES as $deviceName => $roomName) {
            $room = $this->getReference($roomName);

            if (!$room instanceof Room) {
                continue;
            }

            $device = new Device();
            $device->setName($deviceName);
            $device->setRoom($room);

            $manager->persist($device);
            $this->addReference($deviceName, $device);
        }

        $manager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function getDependencies(): array
    {
        return [
            RoomFixtures::class,
        ];
    }
}
