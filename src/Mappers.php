<?php

namespace GW\Value;

use Closure;

final class Mappers
{
    /**
     * @deprecated use method() instead
     */
    public static function callMethod(string $method, ...$args): Closure
    {
        return self::method($method, ...$args);
    }

    public static function method(string $method, ...$args): Closure
    {
        return static function ($item) use ($method, $args) {
            return $item->$method(...$args);
        };
    }

    public static function property($propertyName): Closure
    {
        return static function ($object) use ($propertyName) {
            return $object->$propertyName;
        };
    }

    public static function index($index): Closure
    {
        return static function ($array) use ($index) {
            return $array[$index];
        };
    }

    private function __construct()
    {
    }
}
