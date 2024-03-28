<?php

declare(strict_types=1);

namespace App\User\Application\Query\Handler;

use App\Shared\Application\Query\QueryHandlerInterface;
use App\Shared\Application\Query\QueryInterface;
use App\User\Application\DTO\UserQueryDTO;
use App\User\Domain\ValueObject\Uuid;
use App\User\Infrastructure\Persistence\Doctrine\Repository\UserRepository;

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

        $user = $this->userRepository->find(new Uuid($userId));
        if ($user === null) {
            return new UserQueryDTO('null', 'User not found', 'null');
        }
        return new UserQueryDTO(
            $user->getId()->toString(),
            $user->getUsername()->toString(),
            $user->getEmail()->toString()
        );
    }
}