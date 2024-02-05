<?php

declare(strict_types=1);

namespace App\Application\User\DTO;

class UserDTOBuilder
{
    private ?string $id = null;
    private ?string $username = null;
    private ?string $password = null;
    private ?string $email = null;

    public function setId(?string $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;
        return $this;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function build(): UserDTO
    {
        return new UserDTO($this->id, $this->username, $this->password, $this->email);
    }
}