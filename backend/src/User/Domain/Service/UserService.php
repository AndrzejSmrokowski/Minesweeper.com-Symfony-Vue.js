<?php

declare(strict_types=1);

namespace App\User\Domain\Service;

use App\User\Application\DTO\CreateUserDTO;
use App\User\Application\DTO\UserRegistrationResultDTO;
use App\User\Domain\Exception\UserAlreadyExistsException;
use App\User\Domain\Exception\UserNotFoundException;
use App\User\Domain\Factory\UserFactory;
use App\User\Domain\Repository\UserRepositoryInterface;
use App\User\Domain\ValueObject\Email;
use App\User\Domain\ValueObject\Username;
use App\User\Domain\ValueObject\Uuid;

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
        try {
            $this->userRepository->remove($user);
            $this->userRepository->save();
        } catch (\Exception $e) {
            throw new UserNotFoundException("User with id $userId not found");
        }
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