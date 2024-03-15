<?php

declare(strict_types=1);

namespace App\Domain\User\Exception;

use Exception;

class UserNotFoundException extends Exception
{
    public function __construct(string $userId)
    {
        parent::__construct("User with id $userId not found");
    }
}