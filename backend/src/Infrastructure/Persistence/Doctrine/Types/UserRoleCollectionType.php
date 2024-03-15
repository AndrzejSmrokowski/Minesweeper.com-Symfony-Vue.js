<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Types;

use App\Domain\User\Enum\UserRole;
use App\Domain\User\Enum\UserRoleCollection;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use InvalidArgumentException;

class UserRoleCollectionType extends Type
{
    public function getName(): string
    {
        return 'user_role_collection';
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getJsonTypeDeclarationSQL($column);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?UserRoleCollection
    {
        if ($value === null || $value === '') {
            return null;
        }

        $rolesArray = json_decode($value, true);
        $roles = array_map(fn($role) => UserRole::from($role), $rolesArray);

        return new UserRoleCollection(...$roles);
    }

    /**
     * @throws \Exception
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): string|false
    {
        if (!$value instanceof UserRoleCollection) {
            throw new InvalidArgumentException('Expected a UserRoleCollection instance.');
        }

        $roles = array_map(fn(UserRole $role) => $role->value, iterator_to_array($value->getIterator()));

        return json_encode($roles);
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
