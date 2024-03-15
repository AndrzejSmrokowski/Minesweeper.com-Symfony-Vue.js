<?php

declare(strict_types=1);

namespace App\Infrastructure\EventSubscriber;

use App\Domain\User\Exception\UserAlreadyExistsException;
use App\Domain\User\Exception\UserNotFoundException;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

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

        if ($exception instanceof UserAlreadyExistsException) {
            $this->logger->error($exception->getMessage(), ['exception' => $exception]);
            $response = new JsonResponse(['error' => $exception->getMessage()], Response::HTTP_CONFLICT);
            $event->setResponse($response);
        } elseif ($exception instanceof UserNotFoundException) {
            $this->logger->error($exception->getMessage(), ['exception' => $exception]);
            $response = new JsonResponse(['error' => $exception->getMessage()], Response::HTTP_NOT_FOUND);
            $event->setResponse($response);
        } elseif ($exception instanceof \InvalidArgumentException) {
            $this->logger->error($exception->getMessage(), ['exception' => $exception]);
            $response = new JsonResponse(['error' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
            $event->setResponse($response);
        }
        else {
            $this->logger->error($exception->getMessage(), ['exception' => $exception]);
        }
    }
}