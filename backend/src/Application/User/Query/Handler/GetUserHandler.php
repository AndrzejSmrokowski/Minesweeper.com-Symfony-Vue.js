<?php

declare(strict_types=1);

namespace App\Application\User\Query\Handler;

use App\Application\Shared\Query\QueryHandlerInterface;
use App\Application\Shared\Query\QueryInterface;
use App\Application\User\DTO\UserQueryDTO;
use App\Infrastructure\Persistence\Doctrine\Repository\UserRepository;
use Ramsey\Uuid\Uuid;

class GetUserHandler implements QueryHandlerInterface
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function __invoke(QueryInterface $query): UserQueryDTO
    {
        $userId = $query->getUserId();
        $user = $this->userRepository->find(Uuid::fromString($userId));

        return new UserQueryDTO(
            $user->getId()->toString(),
            $user->getUsername(),
            $user->getEmail()
        );
    }
}