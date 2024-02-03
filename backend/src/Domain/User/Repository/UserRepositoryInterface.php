<?php
declare(strict_types=1);

namespace App\Domain\User\Repository;

use App\Domain\User\Entity\User;

interface UserRepositoryInterface
{
    public function find(int $id): ?User;

    public function findOneBy(array $criteria): ?User;

    public function findAll(): array;

    public function add(User $user): void;

    public function remove(User $user): void;

    public function save(): void;

}