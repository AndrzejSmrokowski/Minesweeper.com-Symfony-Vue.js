<?php

declare(strict_types=1);

namespace App\Application\User\Command\Handler;

use App\Application\Shared\Command\CommandHandlerInterface;
use App\Application\User\Command\UpdateUserCommand;
use App\Application\User\Event\UserUpdatedEvent;
use App\Domain\User\Service\UserService;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class UpdateUserHandler implements CommandHandlerInterface
{
    private EventDispatcherInterface $eventDispatcher;
    private UserService $userService;

    public function __construct(EventDispatcherInterface $eventDispatcher, UserService $userService)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->userService = $userService;
    }

    public function __invoke(UpdateUserCommand $command): void
    {
        $this->userService->updateUser($command->getUserId(), $command->getUsername(), $command->getEmail());

        $event = new UserUpdatedEvent($command->getUserId());
        $this->eventDispatcher->dispatch($event, UserUpdatedEvent::NAME);
    }
}