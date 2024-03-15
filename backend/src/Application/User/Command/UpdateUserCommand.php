<?php

declare(strict_types=1);

namespace App\Application\User\Command;

use App\Application\Shared\Command\CommandInterface;

final readonly class UpdateUserCommand implements CommandInterface
{
    public function __construct(
        private string $userId,
        private string $username,
        private string $email
    ){}

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}