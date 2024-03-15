<?php

declare(strict_types=1);

namespace App\Presentation\API\Controller;

use App\Application\Shared\Command\CommandBusInterface;
use App\Application\Shared\Query\QueryBusInterface;
use App\Application\User\Command\CreateUserCommand;
use App\Application\User\Command\DeleteUserCommand;
use App\Application\User\Command\UpdateUserCommand;
use App\Application\User\DTO\UserQueryDTO;
use App\Application\User\Query\GetUserQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Assert\Assert;
use Ramsey\Uuid\Uuid;

class UserController extends AbstractController
{
    private CommandBusInterface $commandBus;
    private QueryBusInterface $queryBus;


    public function __construct(CommandBusInterface $commandBus, QueryBusInterface $queryBus)
    {
        $this->commandBus = $commandBus;
        $this->queryBus = $queryBus;
    }

    #[Route('/users', name: 'create_user', methods: ['POST'])]
    public function createUser(Request $request): JsonResponse{
        $data = json_decode($request->getContent(), true);

        $username = $data['username'] ?? null;
        $email = $data['email'] ?? null;
        $password = $data['password'] ?? null;

        Assert::that($username)->notNull()->string();
        Assert::that($email)->notNull()->string();
        Assert::that($password)->notNull()->string();

        $uuid = Uuid::uuid4();
        $command = new CreateUserCommand($uuid->toString(), $username, $email, $password);

        $this->commandBus->dispatch($command);

        return new JsonResponse(['message' => 'User created successfully', 'userId' => $uuid->toString()],
            Response::HTTP_CREATED);
    }

    #[Route('/users/{id}', name: 'get_user', methods: ['GET'])]
    public function getUserById(string $id): JsonResponse
    {
        /** @var UserQueryDTO $queryResponse */
        $queryResponse = $this->queryBus->handle(new GetUserQuery($id));

        $userData = [
            'id' => $queryResponse->getId(),
            'username' => $queryResponse->getUsername(),
            'email' => $queryResponse->getEmail(),
        ];

        return new JsonResponse($userData, Response::HTTP_OK);
    }

    #[Route('/users/{id}', name: 'delete_user', methods: ['DELETE'])]
    public function deleteUser(string $id): JsonResponse
    {
        $this->commandBus->dispatch(new DeleteUserCommand($id));

        return new JsonResponse(['message' => 'User deleted successfully'], Response::HTTP_OK);
    }

    #[Route('/users/{id}', name: 'update_user', methods: ['PUT'])]
    public function updateUser(string $id, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $username = $data['username'] ?? null;
        $email = $data['email'] ?? null;
        $password = $data['password'] ?? null;

        Assert::that($username)->notNull()->string();
        Assert::that($email)->notNull()->string();
        Assert::that($password)->notNull()->string();

        $command = new UpdateUserCommand($id, $username, $email);

        $this->commandBus->dispatch($command);

        return new JsonResponse(['message' => 'User updated successfully'], Response::HTTP_OK);
    }





}