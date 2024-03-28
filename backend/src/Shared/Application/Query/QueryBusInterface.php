<?php

declare(strict_types=1);

namespace App\Shared\Application\Query;

interface QueryBusInterface
{
    /**
     * Handles the given query and returns the result.
     *
     * @param QueryInterface $query The query to handle.
     * @return QueryResponseInterface The result of the query.
     */
    public function handle(QueryInterface $query): QueryResponseInterface;
}
