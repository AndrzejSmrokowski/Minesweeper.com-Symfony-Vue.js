<?php

declare(strict_types=1);

namespace App\Presentation\API\Controller;

use App\Application\Shared\Query\QueryBusInterface;
use App\Application\Shared\Service\DTOValidator;
use App\Application\User\Command\CreateUserCommand;
use App\Application\User\DTO\CreateUserDTO;
use App\Application\User\Query\GetUserQuery;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Exception\ValidatorException;

class UserController
{
    private MessageBusInterface $commandBus;
    private QueryBusInterface $queryBus;
    private DTOValidator $dtoValidator;
    private SerializerInterface $serializer;

    public function __construct(MessageBusInterface $commandBus, QueryBusInterface $queryBus, DTOValidator $dtoValidator, SerializerInterface $serializer)
    {
        $this->commandBus = $commandBus;
        $this->queryBus = $queryBus;
        $this->dtoValidator = $dtoValidator;
        $this->serializer = $serializer;
    }

    #[Route('/api/user', name: 'create_user', methods: ['POST'])]
    public function createUser(Request $request): Response
    {
        try {
            $createUserDTO = $this->serializer->deserialize($request->getContent(), CreateUserDTO::class, 'json');
            $this->dtoValidator->validate($createUserDTO);

            $command = new CreateUserCommand($createUserDTO);
            $this->commandBus->dispatch($command);

            return new JsonResponse(['message' => 'User created successfully'], Response::HTTP_CREATED);
        } catch (ValidatorException $exception) {
            return new JsonResponse(['errors' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/api/user/{id}', name: 'get_user', methods: ['GET'])]
    public function getUser(Request $request): Response
    {
        $id = $request->get('id');

        $user = $this->queryBus->dispatch(new GetUserQuery($id));

        return new Response(json_encode($user), Response::HTTP_OK);
    }

}