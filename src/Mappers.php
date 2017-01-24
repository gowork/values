<?php

namespace GW\Value;

final class Mappers
{
    public static function callMethod(string $method, ...$args): \Closure
    {
        return function ($item) use ($method, $args) {
            return $item->$method(...$args);
        };
    }

    private function __construct()
    {
    }
}
