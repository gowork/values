<?php

namespace GW\Value;

final class Wrap
{
    /**
     * @template TValue
     * @phpstan-param array<mixed, TValue> $array
     * @phpstan-return ArrayValue<TValue>
     */
    public static function array(array $array = []): ArrayValue
    {
        return new PlainArray($array);
    }

    /**
     * @template TKey
     * @template TValue
     * @phpstan-param iterable<TKey, TValue> $iterator
     * @phpstan-return InfiniteIterableValue<TKey, TValue>
     */
    public static function iterable(iterable $iterator = []): InfiniteIterableValue
    {
        return new InfiniteIterableValue($iterator);
    }

    /**
     * @template TValue
     * @phpstan-param TValue ...$array
     * @phpstan-return ArrayValue<TValue>
     */
    public static function arrayFromValues(...$array): ArrayValue
    {
        return new PlainArray($array);
    }

    /**
     * @template TKey of int|string
     * @template TValue
     * @phpstan-param array<TKey, TValue> $array
     * @phpstan-return AssocValue<TKey, TValue>
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

    /**
     * @param float|int|numeric-string|Numberable $number
     */
    public static function number(float|int|string|Numberable $number): NumberValue
    {
        if ($number instanceof NumberValue) {
            return $number;
        }

        return PlainNumber::from($number);
    }

    /**
     * @param array<int,int|float>|Arrayable<NumberValue>|NumbersArray $numbers
     */
    public static function numbersArray(array|Arrayable|NumbersArray $numbers): NumbersArray
    {
        if ($numbers instanceof NumbersArray) {
            return $numbers;
        }

        if ($numbers instanceof Arrayable) {
            return PlainNumbersArray::fromArrayable($numbers);
        }

        return PlainNumbersArray::fromNumbers(...$numbers);
    }

    private function __construct()
    {
        // prohibits creation objects of this class
    }
}
