<?php

namespace DMarti\ExamplesSymfony5\DataFixtures;

use DMarti\ExamplesSymfony5\Factory\CustomerOrderFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        CustomerOrderFactory::createMany(10);

        $manager->flush();
    }
}
