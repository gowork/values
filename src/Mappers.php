<?php

namespace GW\Value;

final class Mappers
{
    public static function callMethod(string $method, ...$args): callable
    {
        return fn($item) => $item->$method(...$args);
    }

    private function __construct()
    {
    }
}
