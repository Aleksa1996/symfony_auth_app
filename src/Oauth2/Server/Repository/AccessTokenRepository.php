<?php


namespace App\Oauth2\Server\Repository;


use App\Entity\Oauth2AccessToken;
use App\Oauth2\Server\Entity\AccessToken;
use App\Repository\Oauth2AccessTokenRepository;
use App\Repository\Oauth2ClientRepository;
use App\Repository\UserRepository;
use App\Service\Oauth2AccessToken\Oauth2AccessTokenManager;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;

class AccessTokenRepository implements AccessTokenRepositoryInterface
{
    /**
     * @var Oauth2AccessTokenRepository
     */
    private $oauth2AccessTokenRepository;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var Oauth2ClientRepository
     */
    private $oauth2ClientRepository;

    /**
     * @var Oauth2AccessTokenManager
     */
    private $oauth2AccessTokenManager;

    /**
     * AccessTokenRepository constructor.
     *
     * @param Oauth2AccessTokenRepository $oauth2AccessTokenRepository
     * @param UserRepository $userRepository
     * @param Oauth2ClientRepository $oauth2ClientRepository
     * @param Oauth2AccessTokenManager $oauth2AccessTokenManager
     */
    public function __construct(
        Oauth2AccessTokenRepository $oauth2AccessTokenRepository,
        UserRepository $userRepository,
        Oauth2ClientRepository $oauth2ClientRepository,
        Oauth2AccessTokenManager $oauth2AccessTokenManager

    )

    {
        $this->oauth2AccessTokenRepository = $oauth2AccessTokenRepository;
        $this->oauth2ClientRepository = $oauth2ClientRepository;
        $this->userRepository = $userRepository;
        $this->oauth2AccessTokenManager = $oauth2AccessTokenManager;
    }

    /**
     * @inheritDoc
     */
    public function getNewToken(ClientEntityInterface $clientEntity, array $scopes, $userIdentifier = null)
    {
        $accessToken = new AccessToken($userIdentifier, $scopes);
        $accessToken->setClient($clientEntity);

        return $accessToken;
    }

    /**
     * @inheritDoc
     */
    public function persistNewAccessToken(AccessTokenEntityInterface $accessTokenEntity)
    {
        $appAccessToken = new Oauth2AccessToken();

        $appAccessToken->setIdentifier($accessTokenEntity->getIdentifier());

        $appUser = $this->userRepository->find($accessTokenEntity->getUserIdentifier());
        $appAccessToken->setUser($appUser);

        $appClient = $this->oauth2ClientRepository->findActive($accessTokenEntity->getClient()->getIdentifier());
        $appAccessToken->setClient($appClient);

        $appAccessToken->setScopes($this->scopesToArray($accessTokenEntity->getScopes()));
        $appAccessToken->setRevoked(false);
        $appAccessToken->setExpiresAt($accessTokenEntity->getExpiryDateTime());

        $this->oauth2AccessTokenManager->save($appAccessToken);
    }

    /**
     * @inheritDoc
     */
    public function revokeAccessToken($tokenId)
    {
        $appAccessToken = $this->oauth2AccessTokenRepository->findActive($tokenId);

        if ($appAccessToken === null) {
            return;
        }

        $this->oauth2AccessTokenManager->revoke($appAccessToken);
    }

    /**
     * @inheritDoc
     */
    public function isAccessTokenRevoked($tokenId)
    {
        $appAccessToken = $this->oauth2AccessTokenRepository->findActive($tokenId);

        if ($appAccessToken === null) {
            return true;
        }

        return $this->oauth2AccessTokenManager->isRevoked($appAccessToken);
    }

    /**
     * Transform scopes to array
     *
     * @param array $scopes
     * @return array
     */
    private function scopesToArray(array $scopes): array
    {
        return array_map(function ($scope) {
            return $scope->getIdentifier();
        }, $scopes);
    }
}