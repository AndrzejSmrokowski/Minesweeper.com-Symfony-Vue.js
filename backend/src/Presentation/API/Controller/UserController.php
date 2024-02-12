<?php

declare(strict_types=1);

namespace App\Presentation\API\Controller;

use App\Application\Shared\Command\CommandBusInterface;
use App\Application\Shared\Query\QueryBusInterface;
use App\Application\User\Command\CreateUserCommand;
use App\Application\User\DTO\CreateUserDTO;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
    private CommandBusInterface $commandBus;
    private QueryBusInterface $queryBus;
    private LoggerInterface $logger;

    public function __construct(CommandBusInterface $commandBus, QueryBusInterface $queryBus, LoggerInterface $logger)
    {
        $this->commandBus = $commandBus;
        $this->queryBus = $queryBus;
        $this->logger = $logger;
    }

    #[Route('/users', name: 'create_user', methods: ['POST'])]
    public function createUser(
        #[MapRequestPayload(
            validationGroups: ['strict', 'edit'],
            validationFailedStatusCode: Response::HTTP_UNPROCESSABLE_ENTITY
        )]
        CreateUserDTO $createUserDto
    ): JsonResponse{
        $command = new CreateUserCommand($createUserDto);
        try {
            $this->commandBus->dispatch($command);
        } catch (\Exception $exception) {
            $this->logger->error('Error creating user: '.$exception->getMessage());

            return new JsonResponse(['error' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse(['message' => 'User created successfully '], Response::HTTP_CREATED);

    }

//    #[Route('/users/{id}', name: 'get_user', methods: ['GET'])]
//    public function getUser(
//        #[MapQueryParameter('id')] UserId $id
//    ): JsonResponse
//    {
//        $queryResponse = $this->queryBus->handle(new GetUserQuery($id));
//
//        return new JsonResponse(json_encode($queryResponse), Response::HTTP_OK);
//    }

}