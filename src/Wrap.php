<?php

namespace GW\Value;

final class Wrap
{
    /**
     * @template TValue
     * @param array<mixed, TValue> $array
     * @return ArrayValue<TValue>
     */
    public static function array(array $array = []): ArrayValue
    {
        return new PlainArray($array);
    }

    /**
     * @template TKey
     * @template TValue
     * @param iterable<TKey, TValue> $iterator
     * @return InfiniteIterableValue<TKey, TValue>
     */
    public static function iterable(iterable $iterator = []): InfiniteIterableValue
    {
        return new InfiniteIterableValue($iterator);
    }

    /**
     * @template TValue
     * @param array<int, TValue> $array
     * @return ArrayValue<TValue>
     */
    public static function arrayFromValues(...$array): ArrayValue
    {
        return new PlainArray($array);
    }

    /**
     * @template TKey
     * @template TValue
     * @param array<TKey, TValue> $array
     * @return AssocValue<TKey, TValue>
     */
    public static function assocArray(array $array = []): AssocValue
    {
        return new AssocArray($array);
    }

    /**
     * @param string|StringValue $value
     */
    public static function string($value): StringValue
    {
        if ($value instanceof StringValue) {
            return $value;
        }

        return new PlainString($value);
    }

    /**
     * @param string[]|StringValue[] $strings
     */
    public static function stringsArray(array $strings = []): StringsArray
    {
        return new PlainStringsArray(self::array($strings));
    }

    private function __construct()
    {
        // prohibits creation objects of this class
    }
}
