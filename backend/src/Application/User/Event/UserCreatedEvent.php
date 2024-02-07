<?php
declare(strict_types=1);

namespace App\Application\User\Event;

use App\Domain\User\ValueObject\UserId;

class UserCreatedEvent
{
    public const string NAME = 'user.created';

    private UserId $userId;

    public function __construct(UserId $userId)
    {
        $this->userId = $userId;
    }

    public function getUserId(): UserId
    {
        return $this->userId;
    }
}