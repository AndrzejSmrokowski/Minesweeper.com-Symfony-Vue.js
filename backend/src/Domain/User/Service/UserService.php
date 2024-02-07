<?php

declare(strict_types=1);

namespace App\Domain\User\Service;

use App\Application\User\DTO\CreateUserDTO;
use App\Application\User\DTO\UserRegistrationResultDTO;
use App\Domain\User\Factory\UserFactory;
use App\Domain\User\Repository\UserRepositoryInterface;

class UserService
{
    private UserFactory $userFactory;
    private UserRepositoryInterface $userRepository;

    public function __construct(UserFactory $userFactory, UserRepositoryInterface $userRepository)
    {
        $this->userFactory = $userFactory;
        $this->userRepository = $userRepository;
    }

    public function createUser(CreateUserDTO $createUserDTO): UserRegistrationResultDTO
    {
        $user = $this->userFactory->createFromDTO($createUserDTO);

        $this->userRepository->add($user);
        $this->userRepository->save();

        return new UserRegistrationResultDTO($user->getId(), $user->getUsername(), $user->getEmail());
    }
}