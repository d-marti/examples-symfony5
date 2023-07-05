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
        ProductFactory::createMany(20);

        $customerOrdersPending = CustomerOrderFactory::new()->pending()->many(5)->create();
        $customerOrderProductFactory = new CustomerOrderProductFactory();

        // create some customer order products associated with the orders and products we just created
        CustomerOrderProductFactory::createMany(25, function () use ($customerOrdersPending, $customerOrderProductFactory) {
            return $customerOrderProductFactory->getUniqueCustomerOrderAndProductPair($customerOrdersPending);
        });

        $customerOrdersPacked = CustomerOrderFactory::new()->packed()->many(3)->create();
        CustomerOrderProductFactory::createMany(15, function () use ($customerOrdersPacked, $customerOrderProductFactory) {
            return $customerOrderProductFactory->getUniqueCustomerOrderAndProductPair($customerOrdersPacked);
        });

        $customerOrdersCancelled = CustomerOrderFactory::new()->cancelled()->many(2)->create();
        CustomerOrderProductFactory::createMany(10, function () use ($customerOrdersCancelled, $customerOrderProductFactory) {
            return $customerOrderProductFactory->getUniqueCustomerOrderAndProductPair($customerOrdersCancelled);
        });

        $manager->flush();
    }
}
