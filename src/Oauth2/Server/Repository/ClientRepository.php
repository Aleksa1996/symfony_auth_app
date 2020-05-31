<?php

namespace App\Oauth2\Server\Repository;

use App\Oauth2\Server\Entity\Client;
use App\Repository\Oauth2ClientRepository;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;

class ClientRepository implements ClientRepositoryInterface
{

    /**
     * @var Oauth2ClientRepository
     */
    private $oauth2ClientRepository;

    public function __construct(Oauth2ClientRepository $oauth2ClientRepository)
    {
        $this->oauth2ClientRepository = $oauth2ClientRepository;
    }

    /**
     * @inheritDoc
     */
    public function getClientEntity($clientIdentifier)
    {
        $appClient = $this->oauth2ClientRepository->findActive($clientIdentifier);

        if (!$appClient) {
            return null;
        }

        return new Client($appClient->getIdentifier(), $appClient->getName(), $appClient->getRedirect());
    }

    /**
     * @inheritDoc
     */
    public function validateClient($clientIdentifier, $clientSecret, $grantType)
    {
        $appClient = $this->oauth2ClientRepository->findActive($clientIdentifier);

        if (!$appClient || !hash_equals($appClient->getSecret(), (string)$clientSecret)) {
            return false;
        }

        return true;
    }
}