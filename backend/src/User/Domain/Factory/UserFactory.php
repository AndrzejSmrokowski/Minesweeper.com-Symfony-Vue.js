<?php

declare(strict_types=1);

namespace App\User\Domain\Factory;

use App\User\Application\DTO\CreateUserDTO;
use App\User\Domain\Entity\User;
use App\User\Domain\Enum\UserRole;
use App\User\Domain\Enum\UserRoleCollection;
use App\User\Domain\ValueObject\Email;
use App\User\Domain\ValueObject\PlainTextPassword;
use App\User\Domain\ValueObject\Username;
use App\User\Domain\ValueObject\Uuid;
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
        $user = new User(
            new Uuid($dto->getUserId()),
            new Username($dto->getUsername()),
            '',
            new Email($dto->getEmail()),
            new UserRoleCollection(UserRole::PLAYER)
        );

        $plainTextPassword = new PlainTextPassword($dto->getPassword());
        $hashedPassword = $this->passwordHasher->hashPassword($user, $plainTextPassword->getPassword());
        $user->changePassword($hashedPassword);

        return $user;
    }
}