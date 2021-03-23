<?php

namespace App\Repository;

use App\Entity\Calificacion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Calificacion|null find($id, $lockMode = null, $lockVersion = null)
 * @method Calificacion|null findOneBy(array $criteria, array $orderBy = null)
 * @method Calificacion[]    findAll()
 * @method Calificacion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CalificacionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Calificacion::class);
    }

    // /**
    //  * @return Calificacion[] Returns an array of Calificacion objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Calificacion
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
