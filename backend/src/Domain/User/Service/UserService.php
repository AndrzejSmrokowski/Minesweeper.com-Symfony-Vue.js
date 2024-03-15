<?php

declare(strict_types=1);

namespace App\Domain\User\Service;

use App\Application\User\DTO\CreateUserDTO;
use App\Application\User\DTO\UserRegistrationResultDTO;
use App\Domain\User\Exception\UserAlreadyExistsException;
use App\Domain\User\Exception\UserNotFoundException;
use App\Domain\User\Factory\UserFactory;
use App\Domain\User\Repository\UserRepositoryInterface;
use App\Domain\User\ValueObject\Email;
use App\Domain\User\ValueObject\Username;
use App\Domain\User\ValueObject\Uuid;

class UserService
{
    private UserFactory $userFactory;
    private UserRepositoryInterface $userRepository;

    public function __construct(UserFactory $userFactory, UserRepositoryInterface $userRepository)
    {
        $this->userFactory = $userFactory;
        $this->userRepository = $userRepository;
    }

    /**
     * @throws UserAlreadyExistsException
     */
    public function createUser(CreateUserDTO $createUserDTO): UserRegistrationResultDTO
    {
        $conflicts = [];

        $existingUserWithEmail = $this->userRepository->findByEmail($createUserDTO->getEmail());
        if ($existingUserWithEmail !== null) {
            $conflicts['email'] = $createUserDTO->getEmail();
        }

        $existingUserWithUsername = $this->userRepository->findByUsername($createUserDTO->getUsername());
        if ($existingUserWithUsername !== null) {
            $conflicts['username'] = $createUserDTO->getUsername();
        }

        if (!empty($conflicts)) {
            throw new UserAlreadyExistsException($conflicts);
        }

        $user = $this->userFactory->createFromDTO($createUserDTO);

        $this->userRepository->add($user);
        $this->userRepository->save();

        return new UserRegistrationResultDTO($user->getId()->toString(), $user->getUsername()->toString(), $user->getEmail()->toString());
    }

    /**
     * @throws UserNotFoundException
     */
    public function deleteUser(string $userId): void
    {
        $uuid = new Uuid($userId);
        $user = $this->userRepository->find($uuid);
        if ($user === null) {
            throw new UserNotFoundException("User with id $userId not found");
        }
        $this->userRepository->remove($user);
        $this->userRepository->save();
    }

    /**
     * @throws UserNotFoundException
     */
    public function updateUser(string $userId, string $username, string $email): void
    {
        $uuid = new Uuid($userId);
        $user = $this->userRepository->find($uuid);
        if ($user === null) {
            throw new UserNotFoundException("User with id $userId not found");
        }

        $user->setUsername(new Username($username));
        $user->setEmail(new Email($email));

        $this->userRepository->save();
    }
}