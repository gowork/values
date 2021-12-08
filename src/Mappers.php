<?php

namespace GW\Value;

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
        return /** @phpstan-return mixed */static fn(object $item) => $item->$method(...$args);
    }

    /**
     * @return callable(object $item): mixed
     */
    public static function property(string $propertyName): callable
    {
        return /** @phpstan-return mixed */static fn(object $object) => $object->$propertyName;
    }

    /**
     * @template TKey of int|string
     * @phpstan-param TKey $index
     * @phpstan-return callable(array<TKey,mixed>):mixed
     */
    public static function index($index): callable
    {
        return static fn(array $array) => $array[$index];
    }

    private function __construct()
    {
    }
}
