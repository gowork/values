<?php

namespace GW\Value;

final class Sorts
{
    public static function asc(): callable
    {
        return
            /**
             * @param mixed $valueA
             * @param mixed $valueB
             */
            static fn($valueA, $valueB): int => $valueA <=> $valueB;
    }

    public static function desc(): callable
    {
        return
            /**
             * @param mixed $valueA
             * @param mixed $valueB
             */
            static fn($valueA, $valueB): int => -1 * ($valueA <=> $valueB);
    }
}
