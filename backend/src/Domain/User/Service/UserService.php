<?php

declare(strict_types=1);

namespace App\Domain\User\Service;

use App\Application\User\DTO\CreateUserDTO;
use App\Application\User\DTO\UserRegistrationResultDTO;
use App\Domain\User\Factory\UserFactory;
use App\Domain\User\Repository\UserRepositoryInterface;
use Psr\Log\LoggerInterface;

class UserService
{
    private UserFactory $userFactory;
    private UserRepositoryInterface $userRepository;
    private LoggerInterface $logger;

    public function __construct(UserFactory $userFactory, UserRepositoryInterface $userRepository, LoggerInterface $logger)
    {
        $this->userFactory = $userFactory;
        $this->userRepository = $userRepository;
        $this->logger = $logger;
    }

    public function createUser(CreateUserDTO $createUserDTO): UserRegistrationResultDTO
    {
        $user = $this->userFactory->createFromDTO($createUserDTO);

        $this->userRepository->add($user);
        $this->userRepository->save();
        $this->logger->info(sprintf('User with id %s was created.', $user->getId()));

        return new UserRegistrationResultDTO($user->getId()->toString(), $user->getUsername(), $user->getEmail());
    }
}