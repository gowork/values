<?php

namespace GW\Value;

final class Wrap
{
    public static function array(array $array = []): PlainArray
    {
        return new PlainArray($array);
    }

    public static function iterable(iterable $iterator = []): InfiniteIterableValue
    {
        return new InfiniteIterableValue($iterator);
    }

    public static function arrayFromValues(...$array): PlainArray
    {
        return new PlainArray($array);
    }

    public static function assocArray(array $array = []): AssocArray
    {
        return new AssocArray($array);
    }

    public static function string(string $value): PlainString
    {
        return new PlainString($value);
    }

    /**
     * @param string[]|StringValue[] $strings
     */
    public static function stringsArray(array $strings = []): PlainStringsArray
    {
        return new PlainStringsArray(self::array($strings));
    }

    private function __construct()
    {
        // prohibits creation objects of this class
    }
}
