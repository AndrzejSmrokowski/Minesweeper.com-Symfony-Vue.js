<?php

declare(strict_types=1);

namespace App\Application\User\Command\Handler;

use App\Application\Shared\Command\CommandHandlerInterface;
use App\Application\User\Command\CreateUserCommand;
use App\Application\User\Event\UserCreatedEvent;
use App\Domain\User\Service\UserService;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Mailer\Messenger\MessageHandler;

class CreateUserHandler implements CommandHandlerInterface
{
    private EventDispatcherInterface $eventDispatcher;
    private UserService $userService;

    public function __construct(EventDispatcherInterface $eventDispatcher, UserService $userService)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->userService = $userService;
    }


    public function __invoke(CreateUserCommand $command): void
    {
        $createUserDTO = $command->getUserDTO();
        $userRegistrationResult = $this->userService->createUser($createUserDTO);

        $event = new UserCreatedEvent($userRegistrationResult->getUserId());
        $this->eventDispatcher->dispatch($event, UserCreatedEvent::NAME);
    }
}