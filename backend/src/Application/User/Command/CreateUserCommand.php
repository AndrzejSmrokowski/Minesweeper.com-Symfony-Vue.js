<?php

declare(strict_types=1);

namespace App\Application\User\Command;

use App\Application\User\DTO\UserDTO;

class CreateUserCommand
{
    private UserDTO $userDTO;

    public function __construct(UserDTO $userDTO)
    {
        $this->userDTO = $userDTO;
    }

    public function getUserDTO(): UserDTO
    {
        return $this->userDTO;
    }
}