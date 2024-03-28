<?php
declare(strict_types=1);

namespace App\User\Domain\Repository;

use App\User\Domain\Entity\User;
use App\User\Domain\ValueObject\UuidInterface;

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