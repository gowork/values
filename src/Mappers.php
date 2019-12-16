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
        return fn(object $item) => $item->$method(...$args);
    }

    /**
     * @return callable(object $item): mixed
     */
    public static function property($propertyName): callable
    {
        return static function ($object) use ($propertyName) {
            return $object->$propertyName;
        };
    }

    /**
     * @return callable(array $item): mixed
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
