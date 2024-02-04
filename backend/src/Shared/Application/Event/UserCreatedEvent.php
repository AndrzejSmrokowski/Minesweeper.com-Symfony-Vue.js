<?php
declare(strict_types=1);

namespace App\Shared\Application\Event;

class UserCreatedEvent
{
    private int $userId;

    public function __construct(int $userId)
    {
        $this->userId = $userId;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }
}