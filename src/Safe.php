<?php

namespace GW\Value;

final class Safe
{
    public static function comparator(callable $callable): \Closure
    {
        return function(...$args) use ($callable): int {
            return self::guard($callable(...$args), 'is_int', 'Comparator must return integer value.');
        };
    }

    public static function filter(callable $callable): \Closure
    {
        return function(...$args) use ($callable): bool {
            return self::guard($callable(...$args), 'is_bool', 'Filter must return boolean value.');
        };
    }

    private static function guard($value, callable $assertion, string $message)
    {
        if ($assertion($value) === false) {
            throw new \InvalidArgumentException($message);
        }

        return $value;
    }

    private function __construct()
    {
        // prohibits creation objects of this class
    }
}
