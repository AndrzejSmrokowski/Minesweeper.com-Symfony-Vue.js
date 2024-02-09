<?php

declare(strict_types=1);

namespace App\Application\User\Query\Handler;

use App\Application\Shared\Query\QueryHandlerInterface;
use App\Application\Shared\Query\QueryInterface;
use App\Application\User\DTO\UserQueryDTO;
use App\Application\User\Query\GetUserQuery;
use App\Infrastructure\Persistence\Doctrine\Repository\UserRepository;

class GetUserHandler implements QueryHandlerInterface
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function __invoke(QueryInterface $query): UserQueryDTO
    {
        $user = $this->userRepository->find($query->getUserId());

        return new UserQueryDTO(
            $user->getId(),
            $user->getUsername(),
            $user->getEmail()
        );
    }
}