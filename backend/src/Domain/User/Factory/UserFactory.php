<?php

declare(strict_types=1);

namespace App\Domain\User\Factory;

use App\Application\User\DTO\CreateUserDTO;
use App\Domain\User\Entity\User;
use App\Domain\User\Enum\UserRole;
use App\Domain\User\Enum\UserRoleCollection;
use App\Domain\User\ValueObject\Email;
use App\Domain\User\ValueObject\PlainTextPassword;
use App\Domain\User\ValueObject\Username;
use App\Domain\User\ValueObject\Uuid;
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