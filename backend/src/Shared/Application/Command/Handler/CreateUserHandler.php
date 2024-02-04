<?php
declare(strict_types=1);

namespace App\Shared\Application\Command\Handler;

use App\Domain\User\Entity\User;
use App\Domain\User\Repository\UserRepositoryInterface;
use App\Shared\Application\Command\CreateUserCommand;
use App\Shared\Application\Event\UserCreatedEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class CreateUserHandler
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