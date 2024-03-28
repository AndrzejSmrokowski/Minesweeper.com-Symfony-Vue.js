<?php

declare(strict_types=1);

namespace App\User\Application\Command\Handler;

use App\Shared\Application\Command\CommandHandlerInterface;
use App\User\Application\Command\UpdateUserCommand;
use App\User\Application\Event\UserUpdatedEvent;
use App\User\Domain\Service\UserService;
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