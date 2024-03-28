<?php

declare(strict_types=1);

namespace App\User\Application\Query;

use App\Shared\Application\Query\QueryInterface;

class GetUserQuery implements QueryInterface
{
    private string $userId;

    public function __construct(string $userId)
    {
        $this->userId = $userId;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }
}