<?php

namespace GW\Value;

use Closure;

final class Mappers
{
    /**
     * @deprecated use method() instead
     * @param array<int, mixed> $args
     * @return callable(object $item): mixed
     */
    public static function callMethod(string $method, ...$args): callable
    {
        return self::method($method, ...$args);
    }

    /**
     * @param array<int, mixed> $args
     * @return callable(object $item): mixed
     */
    public static function method(string $method, ...$args): callable
    {
        return static fn(object $item) => $item->$method(...$args);
    }

    /**
     * @return callable(object $item): mixed
     */
    public static function property(string $propertyName): callable
    {
        return static function ($object) use ($propertyName) {
            return $object->$propertyName;
        };
    }

    /**
     * @template TKey
     * @template TValue
     * @phpstan-param TKey $index
     * @phpstan-return callable(array<TKey, TValue> $item): TValue
     */
    public static function index($index): callable
    {
        return static function ($array) use ($index) {
            return $array[$index];
        };
    }

    private function __construct()
    {
    }
}
