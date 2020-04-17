<?php

namespace App\DataFixtures;

use App\Entity\Configuration;
use App\Entity\Device;
use App\Entity\Room;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class DeviceFixtures extends Fixture implements DependentFixtureInterface
{
    public const TV = 'TV';
    public const LAMP = 'Xiaomi Lampa';
    public const TEMP_SENSOR = 'Temperatura';
    public const TV_LIGHT = 'TV svijetlo';
    public const SOFA_LIGHT = 'Svijetlo kod kauča';
    public const MOTION_SENSOR = 'Senzor pokreta';

    private const DEVICE_NAMES = [
//        self::TV => [
//            'room' => RoomFixtures::LIVING_ROOM,
//            'type' => 'desktop',
//            'deviceId' => 'tv',
//        ],
//        self::LAMP => [
//            'room' => RoomFixtures::BEDROOM,
//            'type' => 'bulb',
//            'deviceId' => 'xiaomilamp',
//        ],
//        self::TEMP_SENSOR => [
//            'room' => RoomFixtures::LIVING_ROOM,
//            'type' => 'alert',
//            'deviceId' => 'tempsensor',
//        ],
        self::TV_LIGHT => [
            'room' => RoomFixtures::LIVING_ROOM,
            'type' => 'bulb',
            'deviceId' => '15ledstrip',
            'configuration' => ConfigurationFixtures::TV_LIGHT_CONFIGURATION,
        ],
        self::SOFA_LIGHT => [
            'room' => RoomFixtures::LIVING_ROOM,
            'type' => 'bulb',
            'deviceId' => '30ledstrip',
            'configuration' => ConfigurationFixtures::SOFA_LIGHT_CONFIGURATION,
        ],
//        self::MOTION_SENSOR => [
//            'room' => RoomFixtures::LIVING_ROOM,
//            'type' => 'heat-map',
//            'deviceId' => 'motionsensor',
//        ],
    ];

    public function load(ObjectManager $manager)
    {
        foreach (self::DEVICE_NAMES as $deviceName => $roomObject) {
            $room = $this->getReference($roomObject['room']);

            if (!$room instanceof Room) {
                continue;
            }

            /** @var Configuration $configuration */
            $configuration = $this->getReference($roomObject['configuration']);

            $device = new Device();
            $device->setName($deviceName);
            $device->setRoom($room);
            $device->setDeviceType($roomObject['type']);
            $device->setDeviceId($roomObject['deviceId']);
            $device->setConfiguration($configuration);

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
            ConfigurationFixtures::class,
            RoomFixtures::class,
        ];
    }
}
