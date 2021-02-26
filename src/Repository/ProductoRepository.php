<?php

namespace App\Repository;

use App\Entity\Orden;
use App\Entity\Producto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

use DateTime;
//use Doctrine\ORM\Query\AST\Join;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\Query\ResultSetMapping;

/**
 * @method Producto|null find($id, $lockMode = null, $lockVersion = null)
 * @method Producto|null findOneBy(array $criteria, array $orderBy = null)
 * @method Producto[]    findAll()
 * @method Producto[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Producto::class);
    }

    /**
    * @return Producto[]
    */
    public function getUploadSince($days, $max) : array
    {
        $date = new DateTime('now - '.$days.' days');

        return $this->createQueryBuilder('p')
            ->andWhere('p.fecha_creacion > :val')
            ->setParameter('val', $date)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults($max)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
    * @return Producto[]
    */
    public function getMostExpensive($max) : array
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.precio_unidad', 'DESC')
            ->setMaxResults($max)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
    * @return Producto[]
    */
    public function getMostPopular($max) : array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = 
        "SELECT producto_id, SUM(cantidad), 
        producto.nombre, producto.precio_unidad, categoria_id
        FROM `orden`
        INNER JOIN `producto`
        ON producto_id = producto.id
        INNER JOIN `categoria`
        ON categoria.id = producto.categoria_id
        WHERE estado = 'pagado'
        GROUP BY producto_id
        ORDER BY sum(cantidad) DESC
        LIMIT 5
        ";

        $stm = $conn->prepare($sql);
        $stm->execute();
        $result = $stm->fetchAllAssociative();
        //die;
        
        return $result;
    }

    /*
    public function findOneBySomeField($value): ?Producto
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
