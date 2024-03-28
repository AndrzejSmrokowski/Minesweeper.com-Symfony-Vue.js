<?php

declare(strict_types=1);

namespace App\User\Domain\ValueObject;

use InvalidArgumentException;

final readonly class Username
{
    private string $username;

    public function __construct(string $username)
    {
        $this->validate($username);
        $this->username = $username;
    }

    public function toString(): string
    {
        return $this->username;
    }

    private function validate(string $username): void
    {
        $length = strlen($username);

        if ($length < 3 || $length > 20) {
            throw new InvalidArgumentException('Username must be between 3 and 20 characters.');
        }

        if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
            throw new InvalidArgumentException('Username can only contain letters, numbers, and underscores.');
        }

        // List of offensive words to check against. This is just an example.
        $offensiveWords = ['badword1', 'badword2'];

        foreach ($offensiveWords as $word) {
            if (str_contains($username, $word)) {
                throw new InvalidArgumentException('Username contains offensive words.');
            }
        }
    }

    public function __toString(): string
    {
        return $this->username;
    }

}