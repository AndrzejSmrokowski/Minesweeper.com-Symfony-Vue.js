<?php

declare(strict_types=1);

namespace App\Domain\User\ValueObject;

use InvalidArgumentException;
use Ramsey\Uuid\Uuid as RamseyUuid;

final class Uuid implements UuidInterface
{
    private string $uuid;

    public function __construct(string $uuid)
    {
        if (!RamseyUuid::isValid($uuid)) {
            throw new InvalidArgumentException(sprintf('Invalid UUID format: "%s" is not a valid UUID.', $uuid));
        }

        $this->uuid = $uuid;
    }

    public function __toString()
    {
        return $this->uuid;
    }

    public function toString(): string
    {
        return $this->uuid;
    }
}