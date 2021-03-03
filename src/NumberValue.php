<?php declare(strict_types=1);

namespace GW\Value;

interface NumberValue extends Value, Numberable
{
    public function toInteger(): int;

    public function toFloat(): float;

    public function toStringValue(): StringValue;

    public function format(int $decimals = 0, string $separator = '.' , string $thousandsSeparator = ','): StringValue;

    // comparators

    /**
     * @return int {-1, 0, 1}
     */
    public function compare(Numberable $other): int;

    public function equals(Numberable $other): bool;

    // basic math

    public function add(Numberable $other): NumberValue;

    public function subtract(Numberable $other): NumberValue;

    public function multiply(Numberable $other): NumberValue;

    public function divide(Numberable $other): NumberValue;

    public function abs(): NumberValue;

    // rounding

    public function round(int $precision = 0, ?int $roundMode = null): NumberValue;

    public function floor(): NumberValue;

    public function ceil(): NumberValue;

    /** @param callable(Numberable):Numberable $formula */
    public function calculate(callable $formula): NumberValue;

    // value

    /** @return bool false when 0, true otherwise */
    public function isEmpty(): bool;
}
