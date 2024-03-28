<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Persistence\Doctrine\Types;

use App\User\Domain\ValueObject\Uuid;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class UuidType extends Type
{
    const string UUID = 'uuid';

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getGuidTypeDeclarationSQL($column);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?Uuid
    {
        if (empty($value)) {
            return null;
        }

        if ($value instanceof Uuid) {
            return $value;
        }

        return new Uuid($value);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): string
    {
        if ($value instanceof Uuid) {
            return (string) $value;
        }

        return (string) new Uuid($value);
    }

    public function getName(): string
    {
        return self::UUID;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}