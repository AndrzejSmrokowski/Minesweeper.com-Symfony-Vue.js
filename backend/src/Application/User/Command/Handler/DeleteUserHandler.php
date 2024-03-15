<?php

declare(strict_types=1);

namespace App\Application\User\Command\Handler;

use App\Application\Shared\Command\CommandHandlerInterface;
use App\Application\User\Command\DeleteUserCommand;
use App\Application\User\Event\UserDeletedEvent;
use App\Domain\User\Exception\UserNotFoundException;
use App\Domain\User\Service\UserService;
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

        $this->eventDispatcher->dispatch($command, UserDeletedEvent::NAME);
    }
}