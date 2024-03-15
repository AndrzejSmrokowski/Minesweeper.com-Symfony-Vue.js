<?php
declare(strict_types=1);

namespace App\Domain\User\Repository;

use App\Domain\User\Entity\User;
use App\Domain\User\ValueObject\UuidInterface;

interface UserRepositoryInterface
{
    public function find(UuidInterface $id): ?User;

    public function findOneBy(array $criteria): ?User;

    public function findByUsername(string $username): ?User;

    public function findByEmail(string $email): ?User;

    public function findAll(): array;

    public function add(User $user): void;

    public function remove(User $user): void;

    public function save(): void;

}