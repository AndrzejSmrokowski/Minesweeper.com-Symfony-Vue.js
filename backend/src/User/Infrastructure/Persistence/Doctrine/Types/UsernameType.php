<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Persistence\Doctrine\Types;

use App\User\Domain\ValueObject\Username;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class UsernameType extends Type
{
    public function getName(): string
    {
        return 'username';
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getStringTypeDeclarationSQL($column);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?Username
    {
        return !empty($value) ? new Username((string)$value) : null;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        return $value instanceof Username ? (string)$value : null;
    }
}