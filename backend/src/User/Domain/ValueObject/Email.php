<?php

declare(strict_types=1);

namespace App\User\Domain\ValueObject;

use InvalidArgumentException;

final readonly class Email
{
    private string $email;

    public function __construct(string $email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException(sprintf('Invalid email format: "%s" is not a valid email address.', $email));
        }

        $this->email = $email;
    }

    public function __toString()
    {
        return $this->email;
    }

    public function toString(): string
    {
        return $this->email;
    }
}
