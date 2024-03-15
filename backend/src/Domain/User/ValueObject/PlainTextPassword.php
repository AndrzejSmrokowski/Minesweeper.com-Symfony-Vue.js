<?php

declare(strict_types=1);

namespace App\Domain\User\ValueObject;

use InvalidArgumentException;

final readonly class PlainTextPassword
{
    private string $password;

    public function __construct(string $password)
    {
        $this->ensureIsValidPassword($password);

        $this->password = $password;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    private function ensureIsValidPassword(string $password): void
    {
        if (strlen($password) < 8) {
            throw new InvalidArgumentException('Password must be at least 8 characters long.');
        }

        if (!preg_match('/[A-Z]/', $password)) {
            throw new InvalidArgumentException('Password must contain at least one uppercase letter.');
        }

        if (!preg_match('/\d/', $password)) {
            throw new InvalidArgumentException('Password must contain at least one digit.');
        }
    }
}