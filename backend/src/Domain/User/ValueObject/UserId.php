<?php

declare(strict_types=1);

namespace App\Domain\User\ValueObject;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class UserId
{
    private UuidInterface $uuid;

    private function __construct(UuidInterface $uuid)
    {
        $this->uuid = $uuid;
    }

    public static function fromString(string $userId): self
    {
        return new self(Uuid::fromString($userId));
    }

    public static function generate(): self
    {
        return new self(Uuid::uuid4());
    }

    public function toString(): string
    {
        return $this->uuid->toString();
    }

    public function equals(UserId $other): bool
    {
        return $this->uuid->equals($other->uuid);
    }
}