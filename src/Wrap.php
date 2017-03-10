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

    /**
     * @param int|float|string|NumberValue $value
     */
    public static function fixedNumber($value, ?int $scale = null): NumberValue
    {
        return FixedNumber::from($value, $scale);
    }

    /**
     * @param int|float|string $value
     */
    public static function number($value): NumberValue
    {
        if (\is_string($value) && \is_numeric($value)) {
            $value = (float)$value;
        }

        if (\is_int($value) || (\is_float($value) && $value === (float)(int)$value)) {
            return new IntegerNumber((int)$value);
        }

        if (\is_float($value)) {
            return new FloatNumber($value);
        }

        throw new \InvalidArgumentException('Provided value is not a number');
    }

    private function __construct()
    {
        // prohibits creation objects of this class
    }
}
