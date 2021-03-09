<?php

namespace App\Repository;

use App\Entity\Orden;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Orden|null find($id, $lockMode = null, $lockVersion = null)
 * @method Orden|null findOneBy(array $criteria, array $orderBy = null)
 * @method Orden[]    findAll()
 * @method Orden[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrdenRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Orden::class);
    }

    /**
    * @return Orden[]
    */
    public function getCarrito($userid) : array
    {
        return $this->createQueryBuilder('o')
            ->where('o.usuario = :user')
            ->andWhere('o.estado = :estado')
            ->setParameter('user', $userid)
            ->setParameter('estado', 'en el carrito')
            ->orderBy('o.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }


    /**
    * @return Orden[]
    */
    public function getMisCompras($userid) : array
    {
        return $this->createQueryBuilder('o')
            ->where('o.usuario = :user')
            ->andWhere('o.estado = :estado')
            ->setParameter('user', $userid)
            ->setParameter('estado', 'pagado')
            ->orderBy('o.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
}