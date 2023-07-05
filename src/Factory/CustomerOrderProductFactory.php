<?php

namespace DMarti\ExamplesSymfony5\Factory;

use DMarti\ExamplesSymfony5\Constant\CustomerOrderStatusFulfillment;
use DMarti\ExamplesSymfony5\Entity\CustomerOrderProduct;
use DMarti\ExamplesSymfony5\Repository\CustomerOrderProductRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<CustomerOrderProduct>
 *
 * @method        CustomerOrderProduct|Proxy                     create(array|callable $attributes = [])
 * @method static CustomerOrderProduct|Proxy                     createOne(array $attributes = [])
 * @method static CustomerOrderProduct|Proxy                     find(object|array|mixed $criteria)
 * @method static CustomerOrderProduct|Proxy                     findOrCreate(array $attributes)
 * @method static CustomerOrderProduct|Proxy                     first(string $sortedField = 'id')
 * @method static CustomerOrderProduct|Proxy                     last(string $sortedField = 'id')
 * @method static CustomerOrderProduct|Proxy                     random(array $attributes = [])
 * @method static CustomerOrderProduct|Proxy                     randomOrCreate(array $attributes = [])
 * @method static CustomerOrderProductRepository|RepositoryProxy repository()
 * @method static CustomerOrderProduct[]|Proxy[]                 all()
 * @method static CustomerOrderProduct[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static CustomerOrderProduct[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static CustomerOrderProduct[]|Proxy[]                 findBy(array $attributes)
 * @method static CustomerOrderProduct[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static CustomerOrderProduct[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class CustomerOrderProductFactory extends ModelFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * Inject services if required
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     */
    protected function getDefaults(): array
    {
        return [
            // These can be considered "default" values in case you don't pass them as arguments
            // when creating a CustomerOrderProductFactory object in your fixtures.
            'customerOrder' => CustomerOrderFactory::new()->pending(),
            'product' => ProductFactory::new(),
            'quantityOrdered' => self::faker()->numberBetween(1, 20),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            ->afterInstantiate(function (CustomerOrderProduct $customerOrderProduct): void {
                switch ($customerOrderProduct->getCustomerOrder()->getStatusFulfillment()) {
                    case CustomerOrderStatusFulfillment::Packed:
                        $customerOrderProduct->setQuantityToPack(0);
                        break;
                    case CustomerOrderStatusFulfillment::Pending:
                        $customerOrderProduct->setQuantityToPack(
                            self::faker()->numberBetween(0, $customerOrderProduct->getQuantityOrdered())
                        );
                        break;
                    default:
                        $customerOrderProduct->setQuantityToPack($customerOrderProduct->getQuantityOrdered());
                }
            });
    }

    protected static function getClass(): string
    {
        return CustomerOrderProduct::class;
    }
}
