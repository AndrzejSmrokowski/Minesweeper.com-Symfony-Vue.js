<?php

declare(strict_types=1);

namespace App\Application\User\DTO;

use App\Application\Shared\Query\QueryResponseInterface;

final readonly class UserQueryDTO implements QueryResponseInterface
{
    public function __construct(
        private string $id,
        private string $username,
        private string $email
    ){}

    public function getId(): string
    {
        return $this->id;
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