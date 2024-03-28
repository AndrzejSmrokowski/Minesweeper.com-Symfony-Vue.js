<?php

declare(strict_types=1);

namespace App\User\Application\DTO;

use SensitiveParameter;

final readonly class CreateUserDTO
{
    public function __construct(
        private string $userId,
        private string $username,
        private string $email,
        #[SensitiveParameter]
        private string $password
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

    public function getPassword(): string
    {
        return $this->password;
    }
}