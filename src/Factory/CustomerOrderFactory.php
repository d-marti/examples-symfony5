<?php

namespace DMarti\ExamplesSymfony5\Factory;

use DateTime;
use DMarti\ExamplesSymfony5\Constant\CustomerOrderStatusFulfillment;
use DMarti\ExamplesSymfony5\Entity\CustomerOrder;
use DMarti\ExamplesSymfony5\Repository\CustomerOrderRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<CustomerOrder>
 *
 * @method        CustomerOrder|Proxy                     create(array|callable $attributes = [])
 * @method static CustomerOrder|Proxy                     createOne(array $attributes = [])
 * @method static CustomerOrder|Proxy                     find(object|array|mixed $criteria)
 * @method static CustomerOrder|Proxy                     findOrCreate(array $attributes)
 * @method static CustomerOrder|Proxy                     first(string $sortedField = 'id')
 * @method static CustomerOrder|Proxy                     last(string $sortedField = 'id')
 * @method static CustomerOrder|Proxy                     random(array $attributes = [])
 * @method static CustomerOrder|Proxy                     randomOrCreate(array $attributes = [])
 * @method static CustomerOrderRepository|RepositoryProxy repository()
 * @method static CustomerOrder[]|Proxy[]                 all()
 * @method static CustomerOrder[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static CustomerOrder[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static CustomerOrder[]|Proxy[]                 findBy(array $attributes)
 * @method static CustomerOrder[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static CustomerOrder[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class CustomerOrderFactory extends ModelFactory
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

    public function fulfilled(): self
    {
        return $this->addState([
            'statusFulfillment' => CustomerOrderStatusFulfillment::Packed,
            'fulfilledAt' => new DateTime(),
        ]);
    }

    public function unfulfilled(): self
    {
        return $this->addState([
            'statusFulfillment' => CustomerOrderStatusFulfillment::Pending,
            'fulfilledAt' => null,
        ]);
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     */
    protected function getDefaults(): array
    {
        $statusFulfillment = self::faker()->randomElement(CustomerOrderStatusFulfillment::cases());

        return [
            'createdAt' => self::faker()->dateTimeBetween('-30 days', '-10 days'),
            'fulfilledAt' => ($statusFulfillment === CustomerOrderStatusFulfillment::Packed ?
                self::faker()->dateTime() :
                null),
            'statusFulfillment' => $statusFulfillment,
            'updatedAt' => self::faker()->dateTimeBetween('-9 days', 'now'),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(CustomerOrder $customerOrder): void {})
        ;
    }

    protected static function getClass(): string
    {
        return CustomerOrder::class;
    }
}
