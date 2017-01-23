<?php

namespace GW\Value;

interface ArrayValue extends \IteratorAggregate, \ArrayAccess, Collection, Stack
{
    /**
     * @return ArrayValue
     */
    public function join(ArrayValue $other);

    /**
     * @return ArrayValue
     */
    public function slice(int $offset, int $length);

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

    /**
     * @param callable $transformer function(mixed $value): mixed
     * @return ArrayValue
     */
    public function map(callable $transformer);

    /**
     * @param callable $transformer function(mixed $value): bool { ... }
     * @return ArrayValue
     */
    public function filter(callable $transformer);

    /**
     * @param callable $callback function(mixed $value): void
     * @return ArrayValue
     */
    public function each(callable $callback);

    public function implode(string $glue): StringValue;

    /**
     * @return ArrayValue
     */
    public function notEmpty();
}
