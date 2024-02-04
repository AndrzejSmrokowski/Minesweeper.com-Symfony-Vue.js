<?php

declare(strict_types=1);

namespace App\Shared\Application\Query\Handler;

use App\Domain\User\Entity\User;
use App\Infrastructure\Persistence\Doctrine\Repository\UserRepository;
use App\Shared\Application\Query\GetUserQuery;

class GetUserHandler
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function handle(GetUserQuery $query): User
    {
        return $this->userRepository->find($query->getUserId());
    }
}