<?php

declare(strict_types=1);

namespace App\Application\User\DTO;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class CreateUserDTO
{
    #[Assert\NotBlank]
    #[Assert\Length(min: 6, max: 50)]
    private string $username;
    #[Assert\NotBlank]
    #[Assert\Length(min: 6, max: 50)]
    private string $password;
    #[Assert\NotBlank]
    #[Assert\Email]
    private string $email;

    public function __construct(string $username,#[\SensitiveParameter] string $password, string $email)
    {
        $this->username = $username;
        $this->password = $password;
        $this->email = $email;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}