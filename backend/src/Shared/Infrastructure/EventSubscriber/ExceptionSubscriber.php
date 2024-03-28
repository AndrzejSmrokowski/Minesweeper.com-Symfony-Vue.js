<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\EventSubscriber;

use App\User\Domain\Exception\UserAlreadyExistsException;
use App\User\Domain\Exception\UserNotFoundException;
use InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Throwable;

class ExceptionSubscriber implements EventSubscriberInterface
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        $this->logger->error($exception->getMessage(), ['exception' => $exception]);

        $response = $this->createResponseForException($exception);

        $event->setResponse($response);
    }

    private function createResponseForException(Throwable $exception): JsonResponse
    {
        $map = [
            UserAlreadyExistsException::class => [Response::HTTP_CONFLICT, $exception->getMessage()],
            UserNotFoundException::class => [Response::HTTP_NOT_FOUND, $exception->getMessage()],
            InvalidArgumentException::class => [Response::HTTP_BAD_REQUEST, $exception->getMessage()],
        ];

        $exceptionClass = get_class($exception);
        $statusCode = $map[$exceptionClass][0] ?? Response::HTTP_INTERNAL_SERVER_ERROR;
        $message = $map[$exceptionClass][1] ?? $exception->getMessage();

        return new JsonResponse(['error' => $message], $statusCode);
    }
}