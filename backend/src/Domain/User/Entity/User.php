<?php
declare(strict_types=1);

namespace App\Domain\User\Entity;

use App\Domain\User\Enum\UserRole;
use App\Domain\User\ValueObject\UserId;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use InvalidArgumentException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: "App\Infrastructure\Persistence\Doctrine\Repository\UserRepository")]
#[ORM\Table(name: "users")]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id, ORM\Column(type: "string", length: 36, unique: true)]
    private UserId $id;

    #[ORM\Column(type: "string", length: 255, unique: true)]
    #[Assert\Length(min: 6)]
    #[Assert\Regex(pattern: '/^[a-zA-Z0-9_]+$/', message: 'Username can only contain letters, numbers and underscores')]

    private string $username;

    #[ORM\Column(type: "string", length: 255)]
    #[Assert\Length(min: 6)]
    #[Assert\Regex(pattern: '/[A-Z]/', message: 'Password must contain at least one uppercase letter')]
    #[Assert\Regex(pattern: '/[a-z]/', message: 'Password must contain at least one lowercase letter')]
    #[Assert\Regex(pattern: '/\d/', message: 'Password must contain at least one number')]
    private string $password;

    #[ORM\Column(type: "string", length: 255, unique: true)]
    #[Assert\Email]
    private string $email;

    #[ORM\Column(type: "string", length: 255)]
    private UserRole $role;

    #[ORM\Column(type: "datetime_immutable")]
    private DateTimeImmutable $createdAt;

    public function __construct(UserId $id, string $username, string $password, string $email, UserRole $role)
    {

        $this->id = $id;
        $this->username = $username;
        $this->password = $password;
        $this->email = $email;
        $this->role = $role;
        $this->createdAt = new DateTimeImmutable();
    }

    public function getId(): UserId
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function changePassword(string $currentPassword, string $newPassword, UserPasswordHasherInterface $passwordHasher): void
    {
        if (!$passwordHasher->isPasswordValid($this, $currentPassword)) {
            throw new InvalidArgumentException('Current password is incorrect');
        }

        $this->password = $passwordHasher->hashPassword($this, $newPassword);
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getRole(): UserRole
    {
        return $this->role;
    }

    public function setRole(UserRole $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getRoles(): array
    {
        return [$this->role];
    }

    public function eraseCredentials(): void
    {
    }

    public function getUserIdentifier(): string
    {
        return $this->id->toString();
    }
}