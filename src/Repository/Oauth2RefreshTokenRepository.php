<?php

namespace App\Repository;

use App\Entity\Oauth2RefreshToken;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Oauth2RefreshToken|null find($id, $lockMode = null, $lockVersion = null)
 * @method Oauth2RefreshToken|null findOneBy(array $criteria, array $orderBy = null)
 * @method Oauth2RefreshToken[]    findAll()
 * @method Oauth2RefreshToken[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class Oauth2RefreshTokenRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Oauth2RefreshToken::class);
    }

    // /**
    //  * @return Oauth2RefreshToken[] Returns an array of Oauth2RefreshToken objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Oauth2RefreshToken
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
