<?php

namespace GW\Value;

use BadMethodCallException;
use IteratorAggregate;
use ArrayAccess;

/**
 * @template TValue
 * @extends Collection<TValue>
 * @extends IteratorAggregate<int, TValue>
 * @extends ArrayAccess<int, TValue>
 */
interface ArrayValue extends Value, Collection, Stack, IteratorAggregate, ArrayAccess
{
    // Collection

    /**
     * @param callable(TValue $value):void $callback
     * @return ArrayValue<TValue>
     */
    public function each(callable $callback): ArrayValue;

    /**
     * @param callable(TValue $valueA, TValue $valueB): int|null $comparator
     * @return ArrayValue<TValue>
     */
    public function unique(?callable $comparator = null): ArrayValue;

    /**
     * @return array<int, TValue>
     */
    public function toArray(): array;

    /**
     * @param callable(TValue $value):bool $filter
     * @return ArrayValue<TValue>
     */
    public function filter(callable $filter): ArrayValue;

    /**
     * @return ArrayValue<TValue>
     */
    public function filterEmpty(): ArrayValue;

    /**
     * @template TNewValue
     * @param callable(TValue $value):TNewValue $transformer
     * @return ArrayValue<TNewValue>
     */
    public function map(callable $transformer): ArrayValue;

    /**
     * @template TNewValue
     * @param callable(TValue $value):iterable<TNewValue> $transformer
     * @return ArrayValue<TNewValue>
     */
    public function flatMap(callable $transformer): ArrayValue;

    /**
     * @template TNewKey
     * @param callable(TValue $value):TNewKey $reducer
     * @return AssocValue<TNewKey, ArrayValue<TValue>>
     */
    public function groupBy(callable $reducer): AssocValue;

    /**
     * @return ArrayValue<array<TValue>>
     */
    public function chunk(int $size): ArrayValue;

    /**
     * @param callable(TValue $valueA, TValue $valueB): int $comparator
     * @return ArrayValue<TValue>
     */
    public function sort(callable $comparator): ArrayValue;

    /**
     * @return ArrayValue<TValue>
     */
    public function shuffle(): ArrayValue;

    /**
     * @return ArrayValue<TValue>
     */
    public function reverse(): ArrayValue;

    // Stack

    /**
     * @param TValue $value
     * @return ArrayValue<TValue>
     */
    public function unshift($value): ArrayValue;

    /**
     * @param TValue|null $value
     * @return ArrayValue<TValue>
     */
    public function shift(&$value = null): ArrayValue;

    /**
     * @param TValue $value
     * @return ArrayValue<TValue>
     */
    public function push($value): ArrayValue;

    /**
     * @param TValue|null $value
     * @return ArrayValue<TValue>
     */
    public function pop(&$value = null): ArrayValue;

    // ArrayAccess

    /**
     * @param int $offset
     */
    public function offsetExists($offset): bool;

    /**
     * @param int $offset
     * @return TValue
     */
    public function offsetGet($offset);

    /**
     * @param int $offset
     * @param TValue $value
     * @throws BadMethodCallException For immutable types.
     */
    public function offsetSet($offset, $value): void;

    /**
     * @param int $offset
     * @return void
     * @throws BadMethodCallException For immutable types.
     */
    public function offsetUnset($offset): void;

    // ArrayValue own

    /**
     * @param ArrayValue<TValue> $other
     * @return ArrayValue<TValue>
     */
    public function join(ArrayValue $other): ArrayValue;

    /**
     * @return ArrayValue<TValue>
     */
    public function slice(int $offset, int $length): ArrayValue;

    /**
     * @param ArrayValue<TValue> $replacement
     * @return ArrayValue<TValue>
     */
    public function splice(int $offset, int $length, ?ArrayValue $replacement = null): ArrayValue;

    /**
     * @param ArrayValue<TValue> $other
     * @param callable(TValue $valueA, TValue $valueB): int|null $comparator
     * @return ArrayValue<TValue>
     */
    public function diff(ArrayValue $other, ?callable $comparator = null): ArrayValue;

    /**
     * @param ArrayValue<TValue> $other
     * @param callable(TValue $valueA, TValue $valueB): int|null $comparator
     * @return ArrayValue<TValue>
     */
    public function intersect(ArrayValue $other, ?callable $comparator = null): ArrayValue;

    /**
     * @template TNewValue
     * @param callable(TNewValue $reduced, TValue $value):TNewValue $transformer
     * @param TNewValue $start
     * @return TNewValue
     */
    public function reduce(callable $transformer, $start);

    public function implode(string $glue): StringValue;

    /**
     * @return ArrayValue<TValue>
     */
    public function notEmpty(): ArrayValue;

    /**
     * @return AssocValue<int, TValue>
     */
    public function toAssocValue(): AssocValue;

    public function toStringsArray(): StringsArray;
}
