<?php

declare(strict_types=1);

namespace App\User\Application\Event;

class UserDeletedEvent
{
    public const string NAME = 'user.deleted';

    private string $userId;

    public function __construct(string $userId)
    {
        $this->userId = $userId;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }
}