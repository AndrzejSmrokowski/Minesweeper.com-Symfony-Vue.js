<?php

declare(strict_types=1);

namespace App\Application\Shared\Command;

use ReflectionClass;
use ReflectionException;
use ReflectionNamedType;

final class HandlerBuilder
{
    /**
     * @throws ReflectionException
     */
    public static function fromCallables(iterable $callables) : array
    {
        $callablesHandlers = [];

        foreach ($callables as $callable) {
            $envelop = self::extractFirstParam($callable);

            if (! array_key_exists($envelop, $callablesHandlers)) {
                $callablesHandlers[self::extractFirstParam($callable)] = [];
            }

            $callablesHandlers[self::extractFirstParam($callable)][] = $callable;
        }

        return $callablesHandlers;
    }

    /**
     * @throws ReflectionException
     */
    private static function extractFirstParam(object|string $class) : string|null
    {
        $reflection = new ReflectionClass($class);
        $method     = $reflection->getMethod('__invoke');

        if ($method->getNumberOfParameters() === 1) {
            $type = $method->getParameters()[0]->getType();
            if ($type instanceof ReflectionNamedType) {
                return $type->getName();
            }
        }

        return null;
    }
}