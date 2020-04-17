<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

abstract class BaseFixture extends Fixture
{
    /** @var ObjectManager */
    private $manager;

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;
        $this->loadData($manager);
    }

    abstract protected function loadData(ObjectManager $manager);

    protected function createOne(string $className, string $reference, callable $factory)
    {
        $entity = new $className();
        $factory($entity);
        $this->manager->persist($entity);
        $this->addReference($reference, $entity);
    }

    protected function createMany(string $className, int $count, string $reference, callable $factory)
    {
        for ($i = 0; $i < $count; ++$i) {
            $entity = new $className();
            $factory($entity, $i);
            $this->manager->persist($entity);
            $this->addReference($reference.'_'.$i, $entity);
        }
    }

    protected function getOne(string $reference)
    {
        return $this->getReference($reference);
    }

    protected function getMany(string $reference, int $count)
    {
        $references = [];

        for ($i = 0; $i < $count; ++$i) {
            $references[] = $this->getReference($reference.'_'.$i);
        }

        return $references;
    }

}
