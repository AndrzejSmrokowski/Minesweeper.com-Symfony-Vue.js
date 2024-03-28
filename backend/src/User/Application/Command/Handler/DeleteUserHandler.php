<?php

declare(strict_types=1);

namespace App\User\Application\Command\Handler;

use App\Shared\Application\Command\CommandHandlerInterface;
use App\User\Application\Command\DeleteUserCommand;
use App\User\Application\Event\UserDeletedEvent;
use App\User\Domain\Exception\UserNotFoundException;
use App\User\Domain\Service\UserService;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class DeleteUserHandler implements CommandHandlerInterface
{
    private EventDispatcherInterface $eventDispatcher;
    private UserService $userService;

    public function __construct(EventDispatcherInterface $eventDispatcher, UserService $userService)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->userService = $userService;
    }

    /**
     * @throws UserNotFoundException
     */
    public function __invoke(DeleteUserCommand $command): void
    {
        $this->userService->deleteUser($command->getUserId());

        $userDeletedEvent = new UserDeletedEvent($command->getUserId());
        $this->eventDispatcher->dispatch($userDeletedEvent, UserDeletedEvent::NAME);
    }
}