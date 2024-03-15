<?php

declare(strict_types=1);

namespace App\Application\User\Event;

class UserUpdatedEvent
{
    public const string NAME = 'user.updated';

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