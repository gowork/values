<?php

namespace GW\Value;

final class Sorts
{
    public static function asc(): callable
    {
        return fn($valueA, $valueB): int => $valueA <=> $valueB;
    }

    public static function desc(): callable
    {
        return fn($valueA, $valueB): int => -1 * ($valueA <=> $valueB);
    }
}
