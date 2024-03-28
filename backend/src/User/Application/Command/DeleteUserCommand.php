<?php

declare(strict_types=1);

namespace App\User\Application\Command;

use App\Shared\Application\Command\CommandInterface;

final readonly class DeleteUserCommand implements CommandInterface
{
    public function __construct(
        private string $userId
    ) {}

    public function getUserId(): string
    {
        return $this->userId;
    }
}