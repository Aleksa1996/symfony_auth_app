<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserDataPersister implements ContextAwareDataPersisterInterface
{

    /**
     * @var DataPersisterInterface
     */
    private $decorated;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * UserDataPersister constructor.
     *
     * @param DataPersisterInterface $decorated
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(DataPersisterInterface $decorated, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->decorated = $decorated;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * Supports
     *
     * @param $data
     * @param array $context
     * @return bool
     */
    public function supports($data, array $context = []): bool
    {
        return $data instanceof User;
    }

    /**
     * Persist
     *
     * @param $data
     * @param array $context
     * @return object|void
     */
    public function persist($data, array $context = [])
    {
        $data->setPassword(
            $this->passwordEncoder->encodePassword($data, $data->getPassword())
        );

        return $this->decorated->persist($data, $context);
    }

    /**
     * Remove
     *
     * @param $data
     * @param array $context
     * @return mixed
     */
    public function remove($data, array $context = [])
    {
        return $this->decorated->remove($data, $context);
    }

}