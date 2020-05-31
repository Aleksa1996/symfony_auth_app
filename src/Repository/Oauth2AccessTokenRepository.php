<?php

namespace App\Repository;

use App\Entity\Oauth2AccessToken;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Oauth2AccessToken|null find($id, $lockMode = null, $lockVersion = null)
 * @method Oauth2AccessToken|null findOneBy(array $criteria, array $orderBy = null)
 * @method Oauth2AccessToken[]    findAll()
 * @method Oauth2AccessToken[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class Oauth2AccessTokenRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Oauth2AccessToken::class);
    }

    // /**
    //  * @return Oauth2AccessToken[] Returns an array of Oauth2AccessToken objects
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
    public function findOneBySomeField($value): ?Oauth2AccessToken
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /**
     * Find active oauth2 access token via identifier
     *
     * @param $identifier
     * @return Oauth2AccessToken|null
     */
    public function findActive($identifier)
    {
        return $this->findOneBy(['revoked' => 0, 'identifier' => $identifier]);
    }
}
