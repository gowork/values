<?php

namespace GW\Value;

class Sorts
{
    public static function asc(): callable
    {
        return function ($valueA, $valueB): int {
            return $valueA <=> $valueB;
        };
    }

    public static function desc(): callable
    {
        return function ($valueA, $valueB): int {
            return -1 * ($valueA <=> $valueB);
        };
    }
}
