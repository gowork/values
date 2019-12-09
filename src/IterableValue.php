<?php

namespace GW\Value;

use IteratorAggregate;
interface IterableValue extends IteratorAggregate
{
    // IterableValue own

    /**
     * @param callable $callback function(mixed $value): void
     * @return IterableValue
     */
    public function each(callable $callback): IterableValue;

    /**
     * @param callable $filter function(mixed $value): bool { ... }
     * @return IterableValue
     */
    public function filter(callable $filter): IterableValue;

    /**
     * @return IterableValue
     */
    public function filterEmpty(): IterableValue;

    /**
     * @param callable $transformer function(mixed $value): mixed { ... }
     * @return IterableValue
     */
    public function map(callable $transformer): IterableValue;

    /**
     * @param callable $transformer function(mixed $value): iterable { ... }
     * @return IterableValue
     */
    public function flatMap(callable $transformer): IterableValue;

    /**
     * @return IterableValue
     */
    public function join(iterable $other): IterableValue;

    /**
     * @return IterableValue
     */
    public function slice(int $offset, int $length): IterableValue;

    /**
     * @param callable|null $comparator function(mixed $valueA, mixed $valueB): int{-1, 0, 1}
     * @return IterableValue
     */
    public function unique(?callable $comparator = null): IterableValue;

    /**
     * @param callable $transformer function(mixed $reduced, mixed $value): mixed
     * @param mixed $start
     * @return mixed
     */
    public function reduce(callable $transformer, $start);

    /**
     * @return IterableValue
     */
    public function notEmpty(): IterableValue;

    /**
     * @param mixed $value
     * @return IterableValue
     */
    public function unshift($value): IterableValue;

    /**
     * @param mixed $value
     * @return IterableValue
     */
    public function push($value): IterableValue;

    /**
     * @param callable|null $comparator function(mixed $valueA, mixed $valueB): int{-1, 0, 1}
     * @return IterableValue
     */
    public function diff(ArrayValue $other, ?callable $comparator = null): IterableValue;

    /**
     * @param callable|null $comparator function(mixed $valueA, mixed $valueB): int{-1, 0, 1}
     * @return IterableValue
     */
    public function intersect(ArrayValue $other, ?callable $comparator = null): IterableValue;

    /**
     * @return mixed
     */
    public function first();

    /**
     * @return mixed
     */
    public function last();

    public function toArrayValue(): ArrayValue;

    /**
     * @return mixed[]
     */
    public function toArray(): array;

    public function use(iterable $iterable): IterableValue;

    /**
     * @return IterableValue
     */
    public function chunk(int $size): IterableValue;

    /**
     * @return IterableValue
     */
    public function flatten(): IterableValue;

    /**
     * @param callable $filter function(mixed $value): bool
     */
    public function any(callable $filter): bool;

    /**
     * @param callable $filter function(mixed $value): bool
     */
    public function every(callable $filter): bool;

    /**
     * @param callable $filter function(mixed $value): bool
     * @return mixed
     */
    public function find(callable $filter);

    /**
     * @param callable $filter function(mixed $value): bool
     * @return mixed
     */
    public function findLast(callable $filter);
}
