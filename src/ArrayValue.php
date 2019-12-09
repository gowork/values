<?php

namespace GW\Value;

use IteratorAggregate;
use ArrayAccess;
interface ArrayValue extends Value, Collection, Stack, IteratorAggregate, ArrayAccess
{
    // Collection

    /**
     * @param callable $callback function(mixed $value): void
     * @return ArrayValue
     */
    public function each(callable $callback): ArrayValue;

    /**
     * @param callable|null $comparator function(mixed $valueA, mixed $valueB): int{-1, 0, 1}
     * @return ArrayValue
     */
    public function unique(?callable $comparator = null): ArrayValue;

    /**
     * @return mixed[]
     */
    public function toArray(): array;

    /**
     * @param callable $filter function(mixed $value): bool
     * @return ArrayValue
     */
    public function filter(callable $filter): ArrayValue;

    /**
     * @return ArrayValue
     */
    public function filterEmpty(): ArrayValue;

    /**
     * @param callable $transformer function(mixed $value): mixed
     * @return ArrayValue
     */
    public function map(callable $transformer): ArrayValue;

    /**
     * @param callable $transformer function(mixed $value): iterable
     * @return ArrayValue
     */
    public function flatMap(callable $transformer): ArrayValue;

    /**
     * @param callable $reducer function(mixed $value): string|int|bool
     * @return AssocValue AssocValue<ArrayValue>
     */
    public function groupBy(callable $reducer): AssocValue;

    /**
     * @return ArrayValue
     */
    public function chunk(int $size): ArrayValue;

    /**
     * @param callable $comparator function(mixed $valueA, mixed $valueB): int{-1, 0, 1}
     * @return ArrayValue
     */
    public function sort(callable $comparator): ArrayValue;

    /**
     * @return ArrayValue
     */
    public function shuffle(): ArrayValue;

    /**
     * @return ArrayValue
     */
    public function reverse(): ArrayValue;

    // Stack

    /**
     * @param mixed $value
     * @return ArrayValue
     */
    public function unshift($value): ArrayValue;

    /**
     * @param mixed $value
     * @return ArrayValue
     */
    public function shift(&$value = null): ArrayValue;

    /**
     * @param mixed $value
     * @return ArrayValue
     */
    public function push($value): ArrayValue;

    /**
     * @param mixed $value
     * @return ArrayValue
     */
    public function pop(&$value = null): ArrayValue;

    // ArrayAccess

    /**
     * @param int $offset
     */
    public function offsetExists($offset): bool;

    /**
     * @param int $offset
     * @return mixed
     */
    public function offsetGet($offset);

    /**
     * @param int $offset
     * @param mixed $value
     * @return void
     * @throws \BadMethodCallException For immutable types.
     */
    public function offsetSet($offset, $value): void;

    /**
     * @param int $offset
     * @return void
     * @throws \BadMethodCallException For immutable types.
     */
    public function offsetUnset($offset): void;

    // ArrayValue own

    /**
     * @return ArrayValue
     */
    public function join(ArrayValue $other): ArrayValue;

    /**
     * @return ArrayValue
     */
    public function slice(int $offset, int $length): ArrayValue;

    /**
     * @return ArrayValue
     */
    public function splice(int $offset, int $length, ?ArrayValue $replacement = null): ArrayValue;

    /**
     * @param callable|null $comparator function(mixed $valueA, mixed $valueB): int{-1, 0, 1}
     * @return ArrayValue
     */
    public function diff(ArrayValue $other, ?callable $comparator = null): ArrayValue;

    /**
     * @param callable|null $comparator function(mixed $valueA, mixed $valueB): int{-1, 0, 1}
     * @return ArrayValue
     */
    public function intersect(ArrayValue $other, ?callable $comparator = null): ArrayValue;

    /**
     * @param callable $transformer function(mixed $reduced, mixed $value): mixed
     * @param mixed $start
     * @return mixed
     */
    public function reduce(callable $transformer, $start);

    public function implode(string $glue): StringValue;

    /**
     * @return ArrayValue
     */
    public function notEmpty(): ArrayValue;

    public function toAssocValue(): AssocValue;

    public function toStringsArray(): StringsArray;
}
