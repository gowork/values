<?php

namespace GW\Value;

final class Strings
{
    private function __construct()
    {
        // prohibits creation objects of this class
    }

    public static function create(string $value): StringValue
    {
        return new PlainString($value);
    }
}
