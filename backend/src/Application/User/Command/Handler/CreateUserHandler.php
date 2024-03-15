<?php

declare(strict_types=1);

namespace App\Application\User\Command\Handler;

use App\Application\Shared\Command\CommandHandlerInterface;
use App\Application\User\Command\CreateUserCommand;
use App\Application\User\DTO\CreateUserDTO;
use App\Application\User\Event\UserCreatedEvent;
use App\Domain\User\Exception\UserAlreadyExistsException;
use App\Domain\User\Service\UserService;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Throwable;

class CreateUserHandler implements CommandHandlerInterface
{
    private EventDispatcherInterface $eventDispatcher;
    private UserService $userService;

    public function __construct(EventDispatcherInterface $eventDispatcher, UserService $userService)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->userService = $userService;
    }


    /**
     * @throws UserAlreadyExistsException|Throwable
     */
    public function __invoke(CreateUserCommand $command): string
    {
        $createUserDTO = new CreateUserDTO($command->getUserId(), $command->getUsername(), $command->getEmail(), $command->getPassword());
        $userRegistrationResult = $this->userService->createUser($createUserDTO);

        $event = new UserCreatedEvent($userRegistrationResult->getUserId());
        $this->eventDispatcher->dispatch($event, UserCreatedEvent::NAME);

        return $userRegistrationResult->getUserId();
    }
}