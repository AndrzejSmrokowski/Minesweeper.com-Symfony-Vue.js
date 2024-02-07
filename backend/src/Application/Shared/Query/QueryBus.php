<?php

declare(strict_types=1);

namespace App\Application\Shared\Query;

use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

class QueryBus implements QueryBusInterface
{
    private MessageBusInterface $messageBus;

    use HandleTrait {
        handle as handleQuery;
    }

    public function __construct(MessageBusInterface $queryBus)
    {
        $this->messageBus = $queryBus;
    }

    /** @return mixed */
    public function handle(QueryInterface $query): QueryResponseInterface
    {
        return $this->handleQuery($query);
    }
}