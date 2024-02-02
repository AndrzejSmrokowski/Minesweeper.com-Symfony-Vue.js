<?php
declare(strict_types=1);

namespace App\Application\User\Query\Handler;

use App\Application\User\Query\GetUserQuery;
use App\Domain\User\Entity\User;
use App\Infrastructure\Persistence\Doctrine\Repository\UserRepository;

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