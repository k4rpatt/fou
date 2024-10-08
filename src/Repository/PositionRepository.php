<?php

namespace App\Repository;

use App\Entity\Position;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Position>
 */
class PositionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Position::class);
    }

    //    /**
    //     * @return Position[] Returns an array of Position objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Position
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
        public function findDernierePosition(Position $position): ?Position
        {

            return $this->createQueryBuilder('p')
                ->andWhere('p.posX = :x')
                ->andWhere('p.posY = :y')
                ->orderBy('p.moment','DESC')
//                ->andWhere('p.serveur = :serveur')
                ->setParameter('x', $position->getPosX())
                ->setParameter('y', $position->getPosY())
//                ->setParameter('serveur', $position->getServeur()->getId())
                ->getQuery()
                ->getOneOrNullResult()
            ;
        }

    public function findDernierePositionv2($x, $y, $serveur): ?Position
    {

        return $this->createQueryBuilder('p')
            ->select('p, MAX(p.moment) as HIDDEN maxi')
            ->andWhere('p.posX = :x')
            ->andWhere('p.posY = :y')
//            ->orderBy('p.moment','DESC')
                ->andWhere('p.serveur = :serveur')
            ->setParameter('x', $x)
            ->setParameter('y', $y)
            ->setParameter('serveur', $serveur)
//            ->setParameter('serveur', $position->getServeur()->getId())
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }
}
