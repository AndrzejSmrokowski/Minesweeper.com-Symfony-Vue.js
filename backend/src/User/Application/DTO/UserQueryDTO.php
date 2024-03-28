<?php

declare(strict_types=1);

namespace App\User\Application\DTO;

use App\Shared\Application\Query\QueryResponseInterface;

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