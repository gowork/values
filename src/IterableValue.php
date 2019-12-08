<?php

namespace GW\Value;

use IteratorAggregate;

/**
 * @template TKey
 * @template TValue
 * @extends IteratorAggregate<TKey, TValue>
 */
interface IterableValue extends IteratorAggregate
{
    // IterableValue own

    /**
     * @param callable(TValue $value):void $callback
     * @return IterableValue<TKey, TValue>
     */
    public function each(callable $callback): IterableValue;

    /**
     * @param callable(TValue $value):bool $filter
     * @return IterableValue<TKey, TValue>
     */
    public function filter(callable $filter): IterableValue;

    /**
     * @return IterableValue<TKey, TValue>
     */
    public function filterEmpty(): IterableValue;

    /**
     * @template TNewValue
     * @param callable(TValue $value): TNewValue $transformer
     * @return IterableValue<TKey, TNewValue>
     */
    public function map(callable $transformer): IterableValue;

    /**
     * @template TNewValue
     * @param callable(TValue $value): iterable<TNewValue> $transformer
     * @return IterableValue<TKey, TValue>
     */
    public function flatMap(callable $transformer): IterableValue;

    /**
     * @param iterable<TKey, TValue> $other
     * @return IterableValue<TKey, TValue>
     */
    public function join(iterable $other): IterableValue;

    /**
     * @return IterableValue<TKey, TValue>
     */
    public function slice(int $offset, int $length): IterableValue;

    /**
     * @param callable(TValue $valueA, TValue $valueB):int | null $comparator
     * @return IterableValue<TKey, TValue>
     */
    public function unique(?callable $comparator = null): IterableValue;

    /**
     * @template TNewValue
     * @param callable(TNewValue $reduced, TValue $value): TNewValue $transformer
     * @param TNewValue $start
     * @return TNewValue
     */
    public function reduce(callable $transformer, $start);

    /**
     * @return IterableValue<TKey, TValue>
     */
    public function notEmpty(): IterableValue;

    /**
     * @param TValue $value
     * @return IterableValue<TKey, TValue>
     */
    public function unshift($value): IterableValue;

    /**
     * @param TValue $value
     * @return IterableValue<TKey, TValue>
     */
    public function push($value): IterableValue;

    /**
     * @param ArrayValue<TValue> $other
     * @param callable(TValue $valueA, TValue $valueB):int | null $comparator
     * @return IterableValue<TKey, TValue>
     */
    public function diff(ArrayValue $other, ?callable $comparator = null): IterableValue;

    /**
     * @param ArrayValue<TValue> $other
     * @param callable(TValue $valueA, TValue $valueB):int | null $comparator
     * @return IterableValue<TKey, TValue>
     */
    public function intersect(ArrayValue $other, ?callable $comparator = null): IterableValue;

    /**
     * @return ?TValue
     */
    public function first();

    /**
     * @return ?TValue
     */
    public function last();

    /**
     * @return ArrayValue<TValue>
     */
    public function toArrayValue(): ArrayValue;

    /**
     * @return TValue[]
     */
    public function toArray(): array;

    public function use(iterable $iterable): IterableValue;

    /**
     * @return IterableValue<TKey, TValue[]>
     */
    public function chunk(int $size): IterableValue;

    /**
     * @return IterableValue<TKey, TValue>
     */
    public function flatten(): IterableValue;

    /**
     * @param callable(TValue $value):bool $filter
     */
    public function any(callable $filter): bool;

    /**
     * @param callable(TValue $value):bool $filter
     */
    public function every(callable $filter): bool;

    /**
     * @param callable(TValue $value):bool $filter
     * @return ?TValue
     */
    public function find(callable $filter);

    /**
     * @param callable(TValue $value):bool $filter
     * @return ?TValue
     */
    public function findLast(callable $filter);
}
