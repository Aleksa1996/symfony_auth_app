<?php


namespace App\Oauth2\Server\Repository;


use App\Oauth2\Server\Entity\Scope;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\ScopeRepositoryInterface;

class ScopeRepository implements ScopeRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function getScopeEntityByIdentifier($identifier)
    {
        if (Scope::hasScope($identifier)) {
            return new Scope($identifier);
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function finalizeScopes(array $scopes, $grantType, ClientEntityInterface $clientEntity, $userIdentifier = null)
    {
        $filteredScopes = [];
        /** @var Scope $scope */
        foreach ($scopes as $scope) {
            $hasScope = Scope::hasScope($scope->getIdentifier());
            if ($hasScope) {
                $filteredScopes[] = $scope;
            }
        }

        return $filteredScopes;
    }
}