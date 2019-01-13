<?php

namespace GW\Value;

final class Wrap
{
    public static function array(array $array = []): ArrayValue
    {
        return new PlainArray($array);
    }

    public static function iterable(iterable $iterator = []): InfiniteIterableValue
    {
        return new InfiniteIterableValue($iterator);
    }

    public static function arrayFromValues(...$array): ArrayValue
    {
        return new PlainArray($array);
    }

    public static function assocArray(array $array = []): AssocValue
    {
        return new AssocArray($array);
    }

    public static function string(string $value): StringValue
    {
        return new PlainString($value);
    }

    /**
     * @param string[]|StringValue[] $strings
     */
    public static function stringsArray(array $strings = []): StringsArray
    {
        return new PlainStringsArray(self::array($strings));
    }

    public static function int(int $value): IntValue
    {
        return new PlainInt($value);
    }

    /**
     * @param int[]|IntValue[] $ints
     */
    public static function intsArray(array $ints = []): IntsArray
    {
        return new PlainIntsArray(self::array($ints));
    }

    private function __construct()
    {
        // prohibits creation objects of this class
    }
}
