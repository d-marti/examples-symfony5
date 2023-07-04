<?php

namespace DMarti\ExamplesSymfony5\DataFixtures;

use DMarti\ExamplesSymfony5\Factory\CustomerOrderFactory;
use DMarti\ExamplesSymfony5\Factory\CustomerOrderProductFactory;
use DMarti\ExamplesSymfony5\Factory\ProductFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $products = ProductFactory::createMany(20);

        $customerOrders = CustomerOrderFactory::createMany(8);
        // also create at least 2 fulfilled orders
        $customerOrders += CustomerOrderFactory::new()->fulfilled()->many(2)->create();

        // create some customer order products associated with the orders and products we just created
        CustomerOrderProductFactory::createMany(30, function () use ($customerOrders, $products) {
            return [
                'customerOrder' => $customerOrders[array_rand($customerOrders)],
                'product' => $products[array_rand($products)]
            ];
        });

        $manager->flush();
    }
}
