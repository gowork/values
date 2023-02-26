<?php declare(strict_types=1);

namespace GW\Value;

interface NumberValue extends Value, Numberable
{
    // comparators

    /**
     * @param float|int|numeric-string|Numberable $other
     * @return int<-1,1>
     */
    public function compare(float|int|string|Numberable $other): int;

    /**
     * @param float|int|numeric-string|Numberable $other
     */
    public function equals(float|int|string|Numberable $other): bool;

    // basic math

    /**
     * @param float|int|numeric-string|Numberable $other
     */
    public function add(float|int|string|Numberable $other): NumberValue;

    /**
     * @param float|int|numeric-string|Numberable $other
     */
    public function subtract(float|int|string|Numberable $other): NumberValue;

    /**
     * @param float|int|numeric-string|Numberable $other
     */
    public function multiply(float|int|string|Numberable $other): NumberValue;

    /**
     * @param float|int|numeric-string|Numberable $other
     */
    public function divide(float|int|string|Numberable $other): NumberValue;

    public function abs(): NumberValue;

    /**
     * @param float|int|numeric-string|Numberable $divider
     */
    public function modulo(float|int|string|Numberable $divider): NumberValue;

    // rounding

    public function round(int $precision = 0, ?int $roundMode = null): NumberValue;

    public function floor(): NumberValue;

    public function ceil(): NumberValue;

    /** @param callable(int|float):(int|float|Numberable) $formula */
    public function calculate(callable $formula): NumberValue;

    // value

    /** @return bool true when 0 or 0.0, false otherwise */
    public function isEmpty(): bool;

    // casting

    public function format(int $decimals = 0, string $separator = '.', string $thousandsSeparator = ','): StringValue;

    public function toStringValue(): StringValue;

    public function toInteger(): int;

    public function toFloat(): float;

    public function __toString(): string;
}
