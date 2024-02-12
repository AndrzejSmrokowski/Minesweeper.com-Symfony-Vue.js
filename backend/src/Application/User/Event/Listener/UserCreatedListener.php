<?php

declare(strict_types=1);

namespace App\Application\User\Event\Listener;

use App\Application\User\Event\UserCreatedEvent;
use Psr\Log\LoggerInterface;

class UserCreatedListener
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function onUserCreated(UserCreatedEvent $event): void
    {
        $this->logger->info(sprintf('User with id %s was created.', $event->getUserId()));
    }
}