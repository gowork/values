<?php

namespace GW\Value;

interface NumbersArray extends ArrayValue
{
    /**
     * @return NumberValue
     */
    public function sum();

    /**
     * @return NumberValue
     */
    public function avg();

    /**
     * @return NumberValue
     */
    public function min();

    /**
     * @return NumberValue
     */
    public function max();

    // ArrayValue

    /**
     * @param callable $callback function(NumberValue $value): void
     * @return NumbersArray
     */
    public function each(callable $callback);

    /**
     * @param callable|null $comparator function(NumberValue $valueA, NumberValue $valueB): int{-1, 0, 1}
     * @return NumbersArray
     */
    public function unique(?callable $comparator = null);

    /**
     * @return NumberValue[]
     */
    public function toArray(): array;

    /**
     * @param callable $filter function(NumberValue $value): bool
     * @return NumbersArray
     */
    public function filter(callable $filter);

    /**
     * @return NumbersArray
     */
    public function filterEmpty();

    /**
     * @param callable $transformer function(NumberValue $value): NumberValue
     * @return NumbersArray
     */
    public function map(callable $transformer);

    /**
     * @param callable $transformer function(NumberValue $value): iterable|NumberValue[]
     * @return NumbersArray
     */
    public function flatMap(callable $transformer);

    /**
     * @param callable $reducer function(NumberValue $value): string|int|bool
     * @return AssocValue AssocValue<ArrayValue<NumberValue>>
     */
    public function groupBy(callable $reducer): AssocValue;

    /**
     * @return ArrayValue ArrayValue<array<NumberValue>>
     */
    public function chunk(int $size);

    /**
     * @param callable $comparator function(NumberValue $valueA, NumberValue $valueB): int{-1, 0, 1}
     * @return NumbersArray
     */
    public function sort(callable $comparator);

    /**
     * @return NumbersArray
     */
    public function shuffle();

    /**
     * @return NumbersArray
     */
    public function reverse();

    // Stack

    /**
     * @param NumberValue|int|float|string $value
     * @return NumbersArray
     */
    public function unshift($value);

    /**
     * @param NumberValue $value
     * @return NumbersArray
     */
    public function shift(&$value = null);

    /**
     * @param NumberValue|int|float|string $value
     * @return NumbersArray
     */
    public function push($value);

    /**
     * @param NumberValue $value
     * @return NumbersArray
     */
    public function pop(&$value = null);

    // ArrayAccess

    /**
     * @param int $offset
     */
    public function offsetExists($offset): bool;

    /**
     * @param int $offset
     * @return NumberValue
     */
    public function offsetGet($offset);

    /**
     * @param int $offset
     * @param mixed $value
     * @return void
     * @throws \BadMethodCallException For immutable types.
     */
    public function offsetSet($offset, $value);

    /**
     * @param int $offset
     * @return void
     * @throws \BadMethodCallException For immutable types.
     */
    public function offsetUnset($offset);

    // ArrayValue own

    /**
     * @return NumbersArray
     */
    public function join(ArrayValue $other);

    /**
     * @return NumbersArray
     */
    public function slice(int $offset, int $length);

    /**
     * @param callable|null $comparator function(NumberValue $valueA, NumberValue $valueB): int{-1, 0, 1}
     * @return NumbersArray
     */
    public function diff(ArrayValue $other, ?callable $comparator = null);

    /**
     * @param callable|null $comparator function(NumberValue $valueA, NumberValue $valueB): int{-1, 0, 1}
     * @return NumbersArray
     */
    public function intersect(ArrayValue $other, ?callable $comparator = null);

    /**
     * @param callable $transformer function(mixed $reduced, NumberValue $value): mixed
     * @param mixed $start
     * @return mixed
     */
    public function reduce(callable $transformer, $start);

    public function implode(string $glue): StringValue;

    /**
     * @return NumbersArray
     */
    public function notEmpty();

    public function toAssocValue(): AssocValue;

    public function toStringsArray(): StringsArray;

    // Collection

    /**
     * @return NumberValue|null
     */
    public function first();

    /**
     * @return NumberValue|null
     */
    public function last();

    /**
     * @param callable $filter function(NumberValue $value): bool
     * @return NumberValue|null
     */
    public function find(callable $filter);

    /**
     * @param callable $filter function(NumberValue $value): bool
     * @return NumberValue|null
     */
    public function findLast(callable $filter);

    /**
     * @param callable $filter function(NumberValue $value): bool
     */
    public function any(callable $filter): bool;

    /**
     * @param callable $filter function(NumberValue $value): bool
     */
    public function every(callable $filter): bool;
}
