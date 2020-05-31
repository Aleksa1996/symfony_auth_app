<?php


namespace App\DataTransformer\User;


use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Dto\User\UserOutput;
use App\Entity\User;

class UserOutputDataTransformer implements DataTransformerInterface
{

    /**
     * @inheritDoc
     */
    public function transform($object, string $to, array $context = [])
    {
        $output = new UserOutput();

        $output->id = $object->getId();
        $output->email = $object->getEmail();
        $output->username = $object->getUsername();
        $output->roles = $object->getRoles();
        $output->avatar = $object->getAvatar();
        $output->createdAt = $object->getCreatedAt();
        $output->updatedAt = $object->getUpdatedAt();

        return $output;
    }

    /**
     * @inheritDoc
     */
    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        return UserOutput::class === $to && $data instanceof User;
    }
}