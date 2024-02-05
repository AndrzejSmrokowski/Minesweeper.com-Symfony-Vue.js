<?php

declare(strict_types=1);

namespace App\Application\User\Command\Handler;

use App\Application\Shared\Command\CommandHandlerInterface;
use App\Application\User\Command\CreateUserCommand;
use App\Application\User\Event\UserCreatedEvent;
use App\Domain\User\Entity\User;
use App\Domain\User\Repository\UserRepositoryInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class CreateUserHandler implements CommandHandlerInterface
{
    private UserRepositoryInterface $userRepository;
    private EventDispatcherInterface $eventDispatcher;

    public function __construct(UserRepositoryInterface $userRepository, EventDispatcherInterface $eventDispatcher)
    {
        $this->userRepository = $userRepository;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function handle(CreateUserCommand $command): void
    {
        $user = new User($command->getUsername(), $command->getPassword());
        $this->userRepository->add($user);

        $this->userRepository->save($user);

        $event = new UserCreatedEvent($user->getId());
        $this->eventDispatcher->dispatch($event, UserCreatedEvent::NAME);
    }
}