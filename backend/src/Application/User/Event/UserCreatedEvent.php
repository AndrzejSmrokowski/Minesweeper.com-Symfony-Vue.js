<?php
declare(strict_types=1);

namespace App\Application\User\Event;

class UserCreatedEvent
{
    public const string NAME = 'user.created';

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