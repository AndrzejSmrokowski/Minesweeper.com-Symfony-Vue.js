<?php

declare(strict_types=1);

namespace App\User\Application\Event\Listener;

use App\User\Application\Event\UserDeletedEvent;
use Psr\Log\LoggerInterface;

class UserDeletedListener
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function onUserDeleted(UserDeletedEvent $event): void
    {
        $this->logger->info('User deleted', ['userId' => $event->getUserId()]);
    }
}