<?php

declare(strict_types=1);

namespace App\Shared\Application\Command;

use InvalidArgumentException;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\Exception\NoHandlerForMessageException;
use Symfony\Component\Messenger\Handler\HandlersLocator;
use Symfony\Component\Messenger\MessageBus;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Middleware\HandleMessageMiddleware;
use Throwable;

class CommandBus implements CommandBusInterface
{
    private MessageBusInterface $bus;

    /**
     * @throws \ReflectionException
     */
    public function __construct(iterable $commandHandlers)
    {
        $this->bus = new MessageBus([
            new HandleMessageMiddleware(
                new HandlersLocator(
                    HandlerBuilder::fromCallables($commandHandlers)
                )
            ),
        ]);
    }

    /**
     * @throws Throwable
     */
    public function dispatch(CommandInterface $command): void
    {
        try {
            $this->bus->dispatch($command);
        } catch (NoHandlerForMessageException $e) {
            throw new InvalidArgumentException(sprintf('The command has not a valid handler: %s', $command::class));
        } catch (HandlerFailedException $e) {
            throw $e->getPrevious();
        }
    }
}
