<?php

declare(strict_types=1);

namespace App\User\Domain\Exception;

use Exception;
use Throwable;

class UserAlreadyExistsException extends Exception
{
    private array $conflicts;

    public function __construct(array $conflicts = [], int $code = 0, Throwable $previous = null)
    {
        $this->conflicts = $conflicts;

        $message = $this->createMessage();
        parent::__construct($message, $code, $previous);
    }

    private function createMessage(): string
    {
        if (empty($this->conflicts)) {
            return 'User already exists with specified details.';
        }

        $details = array_map(function ($field, $value) {
            return "$field: $value";
        }, array_keys($this->conflicts), $this->conflicts);

        return 'User already exists with ' . implode(' and ', $details);
    }

    public function getConflicts(): array
    {
        return $this->conflicts;
    }
}
