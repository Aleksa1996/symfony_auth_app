<?php

namespace App\Oauth2\Server\Repository;

use App\Oauth2\Server\Entity\User;
use Doctrine\ORM\NonUniqueResultException;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserRepository implements UserRepositoryInterface
{

    /**
     * @var \App\Repository\UserRepository
     */
    private $userRepository;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $userPasswordEncoder;

    public function __construct(\App\Repository\UserRepository $userRepository, UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $this->userRepository = $userRepository;
        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    /**
     * @inheritDoc
     *
     * @throws OAuthServerException|NonUniqueResultException
     */
    public function getUserEntityByUserCredentials($username, $password, $grantType, ClientEntityInterface $clientEntity)
    {
        $appUser = $this->userRepository->findOneByUsername($username);
        if ($appUser === null) {
            return null;
        }

        $isPasswordValid = $this->userPasswordEncoder->isPasswordValid($appUser, $password);
        if (!$isPasswordValid) {
            throw OAuthServerException::invalidCredentials();
        }

        return new User($appUser->getId());
    }
}
