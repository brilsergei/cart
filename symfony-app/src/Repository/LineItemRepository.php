<?php

namespace App\Repository;

use App\Entity\LineItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LineItem|null find($id, $lockMode = null, $lockVersion = null)
 * @method LineItem|null findOneBy(array $criteria, array $orderBy = null)
 * @method LineItem[]    findAll()
 * @method LineItem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LineItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LineItem::class);
    }

    /**
     * @return LineItem[] Returns an array of LineItem objects
     */
    public function findByCart($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.cart = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /*
    public function findOneBySomeField($value): ?LineItem
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
