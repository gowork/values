<?php

namespace GW\Value;

interface ArrayValue extends Value, Collection, Stack, Slicable, \ArrayAccess
{
    // Collection

    /**
     * @param callable $callback function(mixed $value): void
     * @return ArrayValue
     */
    public function each(callable $callback);

    /**
     * @param callable|null $comparator function(mixed $valueA, mixed $valueB): int{-1, 0, 1}
     * @return ArrayValue
     */
    public function unique(?callable $comparator = null);

    /**
     * @return mixed[]
     */
    public function toArray(): array;

    /**
     * @param callable $filter function(mixed $value): bool
     * @return ArrayValue
     */
    public function filter(callable $filter);

    /**
     * @return ArrayValue
     */
    public function filterEmpty();

    /**
     * @param callable $transformer function(mixed $value): mixed
     * @return ArrayValue
     */
    public function map(callable $transformer);

    /**
     * @param callable $transformer function(mixed $value): iterable
     * @return ArrayValue
     */
    public function flatMap(callable $transformer);

    /**
     * @param callable $reducer function(mixed $value): string|int|bool
     * @return AssocValue AssocValue<ArrayValue>
     */
    public function groupBy(callable $reducer): AssocValue;

    /**
     * @return ArrayValue
     */
    public function chunk(int $size);

    /**
     * @param callable $comparator function(mixed $valueA, mixed $valueB): int{-1, 0, 1}
     * @return ArrayValue
     */
    public function sort(callable $comparator);

    /**
     * @return ArrayValue
     */
    public function shuffle();

    /**
     * @return ArrayValue
     */
    public function reverse();

    // Stack

    /**
     * @param mixed $value
     * @return ArrayValue
     */
    public function unshift($value);

    /**
     * @param mixed $value
     * @return ArrayValue
     */
    public function shift(&$value = null);

    /**
     * @param mixed $value
     * @return ArrayValue
     */
    public function push($value);

    /**
     * @param mixed $value
     * @return ArrayValue
     */
    public function pop(&$value = null);

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
    public function offsetSet($offset, $value);

    /**
     * @param int $offset
     * @return void
     * @throws \BadMethodCallException For immutable types.
     */
    public function offsetUnset($offset);

    // ArrayValue own

    /**
     * @return ArrayValue
     */
    public function join(ArrayValue $other);

    /**
     * @return ArrayValue
     */
    public function slice(int $offset, int $length);

    /**
     * @return ArrayValue
     */
    public function splice(int $offset, int $length, ?ArrayValue $replacement = null);

    /**
     * @param callable|null $comparator function(mixed $valueA, mixed $valueB): int{-1, 0, 1}
     * @return ArrayValue
     */
    public function diff(ArrayValue $other, ?callable $comparator = null);

    /**
     * @param callable|null $comparator function(mixed $valueA, mixed $valueB): int{-1, 0, 1}
     * @return ArrayValue
     */
    public function intersect(ArrayValue $other, ?callable $comparator = null);

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
    public function notEmpty();

    public function toAssocValue(): AssocValue;

    public function toStringsArray(): StringsArray;
}
