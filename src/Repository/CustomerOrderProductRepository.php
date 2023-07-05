<?php

namespace DMarti\ExamplesSymfony5\Repository;

use DMarti\ExamplesSymfony5\Entity\CustomerOrderProduct;
use DMarti\ExamplesSymfony5\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CustomerOrderProduct>
 *
 * @method CustomerOrderProduct|null find($id, $lockMode = null, $lockVersion = null)
 * @method CustomerOrderProduct|null findOneBy(array $criteria, array $orderBy = null)
 * @method CustomerOrderProduct[]    findAll()
 * @method CustomerOrderProduct[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CustomerOrderProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CustomerOrderProduct::class);
    }

    public function save(CustomerOrderProduct $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CustomerOrderProduct $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public static function criteriaNotPacked(): Criteria
    {
        return Criteria::create()
            ->andWhere(Criteria::expr()->gt('quantityToPack', 0));
    }

    /**
     * @return CustomerOrderProduct[]
     */
    public function findAllNotPackedByOrderId(int $orderId): array
    {
        return $this->createQueryBuilder('orderProduct')
            ->addSelect('product')
            ->leftJoin(
                'orderProduct.product',
                'product'
            )
            ->addCriteria(self::criteriaNotPacked())
            // usually you would pass the order object, but you can also pass it's primary key ID
            ->andWhere('orderProduct.customerOrder = :orderId')->setParameter('orderId', $orderId)
            ->getQuery()
            ->getResult();
    }
}
