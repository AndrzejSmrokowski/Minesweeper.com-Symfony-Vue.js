<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Persistence\Doctrine\Repository;

use App\User\Domain\Entity\User;
use App\User\Domain\Repository\UserRepositoryInterface;
use App\User\Domain\ValueObject\UuidInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;

class UserRepository implements UserRepositoryInterface
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function find(UuidInterface $id): ?User
    {
        return $this->entityManager->find(User::class, $id);
    }

    public function findOneBy(array $criteria): ?User
    {
        return $this->entityManager->getRepository(User::class)->findOneBy($criteria);
    }

    /**
     * @throws NonUniqueResultException
     */
    public function findByUsername(string $username): ?User
    {
        return $this->entityManager->createQueryBuilder()
            ->select('u')
            ->from(User::class, 'u')
            ->where('u.username = :username')
            ->setParameters(['username' => $username])
            ->getQuery()->getOneOrNullResult();
    }

    /**
     * @throws NonUniqueResultException
     */
    public function findByEmail(string $email): ?User
    {
        return $this->entityManager->createQueryBuilder()
            ->select('u')
            ->from(User::class, 'u')
            ->where('u.email = :email')
            ->setParameter('email', $email)
            ->getQuery()->getOneOrNullResult();
    }

    public function findAll(): array
    {
        return $this->entityManager->getRepository(User::class)->findAll();
    }

    public function add(User $user): void
    {
        $this->entityManager->persist($user);
    }

    public function remove(User $user): void
    {
        $this->entityManager->remove($user);
    }

    public function save(): void
    {
        $this->entityManager->flush();
    }
}