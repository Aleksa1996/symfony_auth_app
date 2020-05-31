<?php


namespace App\Oauth2\Server\Repository;


use App\Entity\Oauth2RefreshToken;
use App\Oauth2\Server\Entity\RefreshToken;
use App\Repository\Oauth2AccessTokenRepository;
use App\Repository\Oauth2RefreshTokenRepository;
use App\Service\Oauth2RefreshToken\Oauth2RefreshTokenManager;
use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;

class RefreshTokenRepository implements RefreshTokenRepositoryInterface
{

    /**
     * @var Oauth2RefreshTokenRepository
     */
    private $oauth2RefreshTokenRepository;

    /**
     * @var Oauth2RefreshTokenManager
     */
    private $oauth2RefreshTokenManager;

    /**
     * @var Oauth2AccessTokenRepository
     */
    private $oauth2AccessTokenRepository;

    /**
     * RefreshTokenRepository constructor.
     *
     * @param Oauth2RefreshTokenRepository $oauth2RefreshTokenRepository
     * @param Oauth2AccessTokenRepository $oauth2AccessTokenRepository
     * @param Oauth2RefreshTokenManager $oauth2RefreshTokenManager
     */
    public function __construct(
        Oauth2RefreshTokenRepository $oauth2RefreshTokenRepository,
        Oauth2AccessTokenRepository $oauth2AccessTokenRepository,
        Oauth2RefreshTokenManager $oauth2RefreshTokenManager
    )
    {
        $this->oauth2RefreshTokenRepository = $oauth2RefreshTokenRepository;
        $this->oauth2AccessTokenRepository = $oauth2AccessTokenRepository;
        $this->oauth2RefreshTokenManager = $oauth2RefreshTokenManager;
    }

    /**
     * @inheritDoc
     */
    public function getNewRefreshToken()
    {
        return new RefreshToken();
    }

    /**
     * @inheritDoc
     */
    public function persistNewRefreshToken(RefreshTokenEntityInterface $refreshTokenEntity)
    {
        $appRefreshToken = new Oauth2RefreshToken();

        $appAccessToken = $this->oauth2AccessTokenRepository->findActive(
            $refreshTokenEntity->getAccessToken()->getIdentifier()
        );

        $appRefreshToken->setAccessToken($appAccessToken);
        $appRefreshToken->setRevoked(false);
        $appRefreshToken->setExpiresAt($refreshTokenEntity->getExpiryDateTime());

        $this->oauth2RefreshTokenManager->save($appRefreshToken);
    }

    /**
     * @inheritDoc
     */
    public function revokeRefreshToken($tokenId)
    {
        $appRefreshToken = $this->oauth2RefreshTokenRepository->find($tokenId);

        if ($appRefreshToken === null) {
            return;
        }

        $this->oauth2RefreshTokenManager->revoke($appRefreshToken);
    }

    /**
     * @inheritDoc
     */
    public function isRefreshTokenRevoked($tokenId)
    {
        $appRefreshToken = $this->oauth2RefreshTokenRepository->find($tokenId);

        if ($appRefreshToken === null) {
            return true;
        }

        return $this->oauth2RefreshTokenManager->isRevoked($appRefreshToken);
    }
}