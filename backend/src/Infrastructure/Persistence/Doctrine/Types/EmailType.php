<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use App\Domain\User\ValueObject\Email;

class EmailType extends Type
{
    public function getName(): string
    {
        return 'email';
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getStringTypeDeclarationSQL($column);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): Email
    {
        return new Email($value);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform) : string
    {
        return $value instanceof Email ? (string)$value : $value;
    }
}
