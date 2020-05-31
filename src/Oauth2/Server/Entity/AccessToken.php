<?php


namespace App\Oauth2\Server\Entity;


use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\Traits\AccessTokenTrait;
use League\OAuth2\Server\Entities\Traits\EntityTrait;
use League\OAuth2\Server\Entities\Traits\TokenEntityTrait;

class AccessToken implements AccessTokenEntityInterface
{
    use AccessTokenTrait, EntityTrait, TokenEntityTrait;

    /**
     * AccessToken constructor.
     *
     * @param int|string $userIdentifier
     * @param array $scopes
     */
    public function __construct($userIdentifier, array $scopes = [])
    {
        $this->setUserIdentifier($userIdentifier);

        foreach ($scopes as $scope) {
            $this->addScope($scope);
        }
    }
}