<?php declare(strict_types=1);

namespace GW\Value;

use IteratorAggregate;
use const PHP_INT_MAX;

/**
 * @template TKey
 * @template TValue
 * @extends IteratorAggregate<TKey, TValue>
 */
interface IterableValue extends IteratorAggregate
{
    // IterableValue own

    /**
     * @phpstan-param callable(TValue):void $callback
     * @phpstan-return IterableValue<TKey, TValue>
     */
    public function each(callable $callback): IterableValue;

    /**
     * @phpstan-param callable(TValue):bool $filter
     * @phpstan-return IterableValue<TKey, TValue>
     */
    public function filter(callable $filter): IterableValue;

    /**
     * @phpstan-return IterableValue<TKey, TValue>
     */
    public function filterEmpty(): IterableValue;

    /**
     * @template TNewValue
     * @param callable(TValue,TKey $key=):TNewValue $transformer
     * @phpstan-return IterableValue<TKey, TNewValue>
     */
    public function map(callable $transformer): IterableValue;

    /**
     * @phpstan-template TNewValue
     * @phpstan-param callable(TValue):iterable<TNewValue> $transformer
     * @phpstan-return IterableValue<TKey, TNewValue>
     */
    public function flatMap(callable $transformer): IterableValue;

    /**
     * @phpstan-param iterable<TKey, TValue> $other
     * @phpstan-return IterableValue<TKey, TValue>
     */
    public function join(iterable $other): IterableValue;

    /**
     * @phpstan-return IterableValue<TKey, TValue>
     */
    public function slice(int $offset, ?int $length = null): IterableValue;

    /**
     * @phpstan-return IterableValue<TKey, TValue>
     */
    public function skip(int $length): IterableValue;

    /**
     * @phpstan-return IterableValue<TKey, TValue>
     */
    public function take(int $length): IterableValue;

    /**
     * @phpstan-param (callable(TValue,TValue):int) | null $comparator
     * @phpstan-return IterableValue<TKey, TValue>
     */
    public function unique(?callable $comparator = null): IterableValue;

    /**
     * @template TNewValue
     * @phpstan-param callable(TNewValue,TValue):TNewValue $transformer
     * @phpstan-param TNewValue $start
     * @phpstan-return TNewValue
     */
    public function reduce(callable $transformer, $start);

    /**
     * @phpstan-return IterableValue<TKey, TValue>
     */
    public function notEmpty(): IterableValue;

    /**
     * @phpstan-param TValue $value
     * @phpstan-return IterableValue<TKey, TValue>
     */
    public function unshift($value): IterableValue;

    /**
     * @phpstan-param TValue $value
     * @phpstan-return IterableValue<TKey, TValue>
     */
    public function push($value): IterableValue;

    /**
     * @phpstan-param ArrayValue<TValue> $other
     * @phpstan-param (callable(TValue,TValue):int) | null $comparator
     * @phpstan-return IterableValue<TKey, TValue>
     */
    public function diff(ArrayValue $other, ?callable $comparator = null): IterableValue;

    /**
     * @phpstan-param ArrayValue<TValue> $other
     * @phpstan-param (callable(TValue,TValue):int) | null $comparator
     * @phpstan-return IterableValue<TKey, TValue>
     */
    public function intersect(ArrayValue $other, ?callable $comparator = null): IterableValue;

    /**
     * @phpstan-return ?TValue
     */
    public function first();

    /**
     * @phpstan-return ?TValue
     */
    public function last();

    /**
     * @phpstan-return ArrayValue<TValue>
     */
    public function toArrayValue(): ArrayValue;

    /**
     * @phpstan-return array<int|string, TValue>
     */
    public function toAssocArray(): array;

    /**
     * @phpstan-return AssocValue<int|string, TValue>
     */
    public function toAssocValue(): AssocValue;

    /**
     * @phpstan-return TValue[]
     */
    public function toArray(): array;

    /**
     * @phpstan-return IterableValue<int, array<int, TValue>>
     */
    public function chunk(int $size): IterableValue;

    /**
     * @phpstan-return IterableValue<TKey, TValue>
     */
    public function flatten(): IterableValue;

    /**
     * @param callable(TValue):bool $filter
     */
    public function any(callable $filter): bool;

    /**
     * @param callable(TValue):bool $filter
     */
    public function every(callable $filter): bool;

    /**
     * @param callable(TValue):bool $filter
     * @phpstan-return ?TValue
     */
    public function find(callable $filter);

    /**
     * @param callable(TValue):bool $filter
     * @phpstan-return ?TValue
     */
    public function findLast(callable $filter);

    /**
     * @phpstan-return IterableValue<int, TKey>
     */
    public function keys(): IterableValue;
}
