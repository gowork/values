<?php

namespace GW\Value;

interface NumberValue extends Value
{
    public const ROUND_DEFAULT = PHP_ROUND_HALF_UP;

    // getters

    /**
     * @return int Number of decimal places, ie. 1234.12 has scale = 2
     */
    public function scale(): int;

    public function isInteger(): bool;

    public function isDecimal(): bool;

    public function toInt(): int;

    public function toFloat(): float;

    public function toString(): string;

    public function toStringValue(): StringValue;

    public function format(int $scale = 0, string $point = '.' , string $thousandsSeparator = ','): StringValue;

    // comparators

    /**
     * @return int {-1, 0, 1}
     */
    public function compare(NumberValue $other): int;

    public function equals(NumberValue $other): bool;

    public function greaterThan(NumberValue $other): bool;

    public function lesserThan(NumberValue $other): bool;

    // math

    /**
     * @return NumberValue
     */
    public function add(NumberValue $other);

    /**
     * @return NumberValue
     */
    public function subtract(NumberValue $other);

    /**
     * @return NumberValue
     */
    public function multiply(NumberValue $other);

    /**
     * @return NumberValue
     */
    public function divide(NumberValue $other);

    /**
     * @return NumberValue
     */
    public function abs();

    // rounding

    /**
     * @return NumberValue
     */
    public function round(int $scale = 0, ?int $roundMethod = null);

    /**
     * @return NumberValue
     */
    public function floor(int $scale = 0);

    /**
     * @return NumberValue
     */
    public function ceil(int $scale = 0);

    // value

    /**
     * @return bool false when 0, true otherwise
     */
    public function isEmpty(): bool;
}
