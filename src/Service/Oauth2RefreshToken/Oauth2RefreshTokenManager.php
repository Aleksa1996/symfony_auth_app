<?php


namespace App\Service\Oauth2RefreshToken;


use App\Entity\Oauth2RefreshToken;
use Doctrine\ORM\EntityManagerInterface;

class Oauth2RefreshTokenManager
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * Oauth2 constructor.
     *
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Persist refresh token in db
     *
     * @param Oauth2RefreshToken $refreshToken
     * @return Oauth2RefreshTokenManager
     */
    public function save(Oauth2RefreshToken $refreshToken)
    {
        $this->entityManager->persist($refreshToken);
        $this->entityManager->flush();

        return $this;
    }

    /**
     * Revoke token
     *
     * @param Oauth2RefreshToken $refreshToken
     * @return Oauth2RefreshTokenManager
     */
    public function revoke(Oauth2RefreshToken $refreshToken)
    {
        $refreshToken->setRevoked(true);
        return $this->save($refreshToken);
    }

    /**
     * Check if access token is revoked
     *
     * @param Oauth2RefreshToken $refreshToken
     * @return bool|null
     */
    public function isRevoked(Oauth2RefreshToken $refreshToken)
    {
        return $refreshToken->getRevoked();
    }
}