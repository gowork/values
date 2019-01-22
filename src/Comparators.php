<?php declare(strict_types=1);

namespace GW\Value;

final class Comparators
{
    public static function numbers(): \Closure
    {
        return function (NumberValue $a, NumberValue $b): int {
            return $a->compare($b);
        };
    }

    public static function reversedNumbers(): \Closure
    {
        return function (NumberValue $a, NumberValue $b): int {
            return $b->compare($a);
        };
    }

    private function __construct()
    {
    }
}
