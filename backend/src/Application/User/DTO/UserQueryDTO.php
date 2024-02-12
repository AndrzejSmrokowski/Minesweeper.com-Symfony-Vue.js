<?php

declare(strict_types=1);

namespace App\Application\User\DTO;

use App\Application\Shared\Query\QueryResponseInterface;
use App\Domain\User\Entity\User;

final readonly class UserQueryDTO implements QueryResponseInterface
{
    private string $id;
    private string $username;
    private string $email;

    public function __construct(string $id, string $username, string $email)
    {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
    }

    public static function fromUser(User $user): self
    {
        return new self($user->getId()->toString(), $user->getUsername(), $user->getEmail());
    }

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