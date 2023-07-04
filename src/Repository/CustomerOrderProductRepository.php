<?php

namespace DMarti\ExamplesSymfony5\Repository;

use DMarti\ExamplesSymfony5\Entity\CustomerOrderProduct;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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
}
