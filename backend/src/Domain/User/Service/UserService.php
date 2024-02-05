<?php
declare(strict_types=1);

namespace App\Domain\User\Service;

use App\Application\User\DTO\UserDTO;
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

    public function createUser(UserDTO $userDTO): void
    {
        $user = $this->userFactory->createFromDTO($userDTO);

        $this->userRepository->add($user);
        $this->userRepository->save();
    }
}