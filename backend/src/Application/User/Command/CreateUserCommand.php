<?php

declare(strict_types=1);

namespace App\Application\User\Command;

use App\Application\Shared\Command\CommandInterface;
use App\Application\User\DTO\CreateUserDTO;

final class CreateUserCommand implements CommandInterface
{
    private CreateUserDTO $createUserDTO;

    public function __construct(CreateUserDTO $userDTO)
    {
        $this->createUserDTO = $userDTO;
    }

    public function getUserDTO(): CreateUserDTO
    {
        return $this->createUserDTO;
    }
}