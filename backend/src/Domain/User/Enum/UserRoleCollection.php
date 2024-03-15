<?php

declare(strict_types=1);

namespace App\Domain\User\Enum;

use ArrayIterator;
use IteratorAggregate;
use Traversable;

class UserRoleCollection implements IteratorAggregate
{
    private array $roles;

    public function __construct(UserRole ...$roles)
    {
        $this->roles = $roles;
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->roles);
    }

    public function add(UserRole $role): void
    {
        if (!$this->contains($role)) {
            $this->roles[] = $role;
        }
    }

    public function remove(UserRole $role): void
    {
        $this->roles = array_filter($this->roles, function (UserRole $existingRole) use ($role) {
            return $existingRole !== $role;
        });
    }

    public function contains(UserRole $role): bool
    {
         return in_array($role, $this->roles, true);
    }

}
