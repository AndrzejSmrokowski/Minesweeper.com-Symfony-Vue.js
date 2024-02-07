<?php

declare(strict_types=1);

namespace App\Domain\User\Factory;

use App\Application\User\DTO\CreateUserDTO;
use App\Domain\User\Entity\User;
use App\Domain\User\Enum\UserRole;
use App\Domain\User\ValueObject\UserId;
use Ramsey\Uuid\Uuid;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFactory
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function createFromDTO(CreateUserDTO $dto): User
    {
        $id = UserId::generate();

        $user = new User(
            $id,
            $dto->getUsername(),
            '',
            $dto->getEmail(),
            UserRole::PLAYER
        );

        $user->changePassword('', $dto->getPassword(), $this->passwordHasher);

        return $user;
    }
}