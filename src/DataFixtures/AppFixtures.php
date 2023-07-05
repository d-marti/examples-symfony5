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

        $customerOrdersPending = CustomerOrderFactory::new()->pending()->many(5)->create();
        // create some customer order products associated with the orders and products we just created
        CustomerOrderProductFactory::createMany(25, function () use ($customerOrdersPending, $products) {
            return [
                'customerOrder' => $customerOrdersPending[array_rand($customerOrdersPending)],
                'product' => $products[array_rand($products)]
            ];
        });

        $customerOrdersFulfilled = CustomerOrderFactory::new()->packed()->many(3)->create();
        CustomerOrderProductFactory::createMany(15, function () use ($customerOrdersFulfilled, $products) {
            return [
                'customerOrder' => $customerOrdersFulfilled[array_rand($customerOrdersFulfilled)],
                'product' => $products[array_rand($products)],
            ];
        });

        $customerOrdersCancelled = CustomerOrderFactory::new()->cancelled()->many(2)->create();
        CustomerOrderProductFactory::createMany(10, function () use ($customerOrdersCancelled, $products) {
            return [
                'customerOrder' => $customerOrdersCancelled[array_rand($customerOrdersCancelled)],
                'product' => $products[array_rand($products)],
            ];
        });

        $manager->flush();
    }
}
