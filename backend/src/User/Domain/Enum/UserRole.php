<?php

declare(strict_types=1);

namespace App\User\Domain\Enum;

enum UserRole: string
{
    case PLAYER = 'player';
    case MODERATOR = 'moderator';
    case ADMIN = 'admin';
}