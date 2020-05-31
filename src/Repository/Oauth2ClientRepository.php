<?php

namespace App\Repository;

use App\Entity\Oauth2Client;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Oauth2Client|null find($id, $lockMode = null, $lockVersion = null)
 * @method Oauth2Client|null findOneBy(array $criteria, array $orderBy = null)
 * @method Oauth2Client[]    findAll()
 * @method Oauth2Client[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class Oauth2ClientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Oauth2Client::class);
    }

    // /**
    //  * @return Oauth2Client[] Returns an array of Oauth2Client objects
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
    public function findOneBySomeField($value): ?Oauth2Client
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
     * Find active oauth2 client via identifier
     *
     * @param $identifier
     * @return Oauth2Client|null
     */
    public function findActive($identifier)
    {
        return $this->findOneBy(['active' => 1, 'identifier' => $identifier]);
    }
}
