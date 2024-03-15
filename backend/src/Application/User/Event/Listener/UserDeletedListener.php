<?php

declare(strict_types=1);

namespace App\Application\User\Event\Listener;

use App\Application\User\Event\UserDeletedEvent;

class UserDeletedListener
{
    public function onUserDeleted(UserDeletedEvent $event): void
    {
        // Do something when a user is deleted
    }
}