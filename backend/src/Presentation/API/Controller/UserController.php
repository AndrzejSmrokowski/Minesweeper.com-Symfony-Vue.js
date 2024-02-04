<?php

declare(strict_types=1);

namespace App\Presentation\API\Controller;

use App\Shared\Application\Command\CreateUserCommand;
use App\Shared\Application\Query\GetUserQuery;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;

class UserController
{
    private MessageBusInterface $commandBus;
    private MessageBusInterface $queryBus;

    public function __construct(MessageBusInterface $commandBus, MessageBusInterface $queryBus)
    {
        $this->commandBus = $commandBus;
        $this->queryBus = $queryBus;
    }

    #[Route('/api/user', name: 'create_user', methods: ['POST'])]
    public function createUser(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $command = new CreateUserCommand($data['username'], $data['password']);
        $this->commandBus->dispatch($command);

        return new Response('', Response::HTTP_CREATED);
    }

    #[Route('/api/user/{id}', name: 'get_user', methods: ['GET'])]
    public function getUser(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $id = $data['id'];

        $user = $this->queryBus->dispatch(new GetUserQuery($id));

        return new Response(json_encode($user), Response::HTTP_OK);
    }

}