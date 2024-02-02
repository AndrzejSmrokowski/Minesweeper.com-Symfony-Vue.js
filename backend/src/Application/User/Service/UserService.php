<?php

namespace App\Application\User\Service;

use App\Domain\User\Entity\User;
use App\Domain\User\Repository\UserRepositoryInterface;

class UserService
{
    private $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function createUser(string $username, string $password): User
    {
        $user = new User();
        $user->setUsername($username);
        $user->setPassword($password);

        $this->userRepository->add($user);

        return $user;
    }

}