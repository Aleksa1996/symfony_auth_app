<?php


namespace App\Oauth2\Server\Entity;


use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\Traits\ClientTrait;
use League\OAuth2\Server\Entities\Traits\EntityTrait;

class Client implements ClientEntityInterface
{
    use ClientTrait, EntityTrait;

    /**
     * Client constructor.
     *
     * @param int|string $identifier
     * @param string|null $name
     * @param string|null $redirectUri
     */
    public function __construct($identifier, $name, $redirectUri)
    {
        $this->setIdentifier($identifier);
        $this->name = $name;
        $this->redirectUri = explode(',', $redirectUri);
    }
}