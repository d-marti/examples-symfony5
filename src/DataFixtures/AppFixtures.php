<?php

namespace DMarti\ExamplesSymfony5\DataFixtures;

use DateTimeImmutable;
use DMarti\ExamplesSymfony5\Entity\CustomerOrder;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $customerOrder = new CustomerOrder();
        $statusFulfillment = rand(1, count(CustomerOrder::STATUS_FULFILLMENT_TEXTS));
        $customerOrder->setStatusFulfillment($statusFulfillment);
        $customerOrder->setFulfilledAt(($statusFulfillment === CustomerOrder::STATUS_FULFILLMENT_PACKED ? new DateTimeImmutable() : null));
        $manager->persist($customerOrder);

        $manager->flush();
    }
}
