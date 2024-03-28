<?php
declare(strict_types=1);

namespace App\User\Domain\Entity;

use App\User\Domain\Enum\UserRole;
use App\User\Domain\Enum\UserRoleCollection;
use App\User\Domain\ValueObject\Email;
use App\User\Domain\ValueObject\Username;
use App\User\Domain\ValueObject\Uuid;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: "App\Infrastructure\Persistence\Doctrine\Repository\UserRepository")]
#[ORM\Table(name: "users")]
class User implements PasswordAuthenticatedUserInterface
{
    #[ORM\Id, ORM\Column(type: "uuid", unique: true)]
    private Uuid $id;

    #[ORM\Column(type: "username", length: 255, unique: true)]
    private Username $username;

    #[ORM\Column(type: "string", length: 255)]
    private string $password;

    #[ORM\Column(type: "email", length: 255, unique: true)]
    private Email $email;

    #[ORM\Column(type: "user_role_collection")]
    private UserRoleCollection $roles;

    #[ORM\Column(type: "datetime_immutable")]
    private DateTimeImmutable $createdAt;

    public function __construct(Uuid $userId, Username $username, string $password, Email $email, UserRoleCollection $roles)
    {
        $this->id = $userId;
        $this->username = $username;
        $this->password = $password;
        $this->email = $email;
        $this->roles = $roles;
        $this->createdAt = new DateTimeImmutable();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getUsername(): Username
    {
        return $this->username;
    }

    public function setUsername(Username $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function changePassword(string $hashedPassword): void
    {
        $this->password = $hashedPassword;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function setEmail(Email $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getRoles(): UserRoleCollection
    {
        return $this->roles;
    }

    public function addRole(UserRole $role): void
    {
        if (!$this->roles->contains($role)) {
            $this->roles->add($role);
        }
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

}