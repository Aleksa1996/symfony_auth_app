<?php

namespace App\Provider;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Serializer\Exception\UnsupportedException;

final class UserProvider implements UserProviderInterface
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * UserProvider constructor.
     *
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @inheritDoc
     * @throws NonUniqueResultException
     */
    public function loadUserByUsername(string $username)
    {
        return $this->userRepository->findOneByUsername($username);
    }

    /**
     * Load user by id
     *
     * @param int $id
     *
     * @return User|null
     */
    public function loadUserById(int $id)
    {
        return $this->userRepository->find($id);
    }

    /**
     * @inheritDoc
     *
     * @throws NonUniqueResultException
     */
    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof User) {
            throw new UnsupportedException(sprintf('Instances of "%s" are not supported', \get_class($user)));
        }

        return $this->userRepository->findOneByUsername($user->getUsername());
    }

    /**
     * @inheritDoc
     */
    public function supportsClass(string $class)
    {
        return User::class === $class;
    }

}