<?php

declare(strict_types=1);

namespace App\User\Application\Command\Handler;

use App\Shared\Application\Command\CommandHandlerInterface;
use App\User\Application\Command\CreateUserCommand;
use App\User\Application\DTO\CreateUserDTO;
use App\User\Application\Event\UserCreatedEvent;
use App\User\Domain\Exception\UserAlreadyExistsException;
use App\User\Domain\Service\UserService;
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