<?php

declare(strict_types=1);

namespace App\Application\User\DTO;

use App\Domain\User\ValueObject\UserId;

final class UserRegistrationResultDTO
{
    private UserId $userId;
    private string $username;
    private string $email;

    public function __construct(UserId $userId, string $username, string $email)
    {
        $this->userId = $userId;
        $this->username = $username;
        $this->email = $email;
    }

    public function getUserId(): UserId
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