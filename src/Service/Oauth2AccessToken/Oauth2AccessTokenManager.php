<?php


namespace App\Service\Oauth2AccessToken;


use App\Entity\Oauth2AccessToken;
use Doctrine\ORM\EntityManagerInterface;

class Oauth2AccessTokenManager
{

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * Oauth2AccessTokenManager constructor.
     *
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Persist access token in db
     *
     * @param Oauth2AccessToken $accessToken
     * @return Oauth2AccessTokenManager
     */
    public function save(Oauth2AccessToken $accessToken)
    {
        $this->entityManager->persist($accessToken);
        $this->entityManager->flush();

        return $this;
    }

    /**
     * Revoke token
     *
     * @param Oauth2AccessToken $accessToken
     * @return Oauth2AccessTokenManager
     */
    public function revoke(Oauth2AccessToken $accessToken)
    {
        $accessToken->setRevoked(true);
        return $this->save($accessToken);
    }

    /**
     * Check if access token is revoked
     *
     * @param Oauth2AccessToken $accessToken
     * @return bool|null
     */
    public function isRevoked(Oauth2AccessToken $accessToken)
    {
        return (bool)$accessToken->getRevoked();
    }
}