<?php

namespace GW\Value;

interface IntsArray extends ArrayValue, IntValue
{
    // Array Value

    /**
     * @param callable $callback function(IntValue $value): void
     * @return IntsArray
     */
    public function each(callable $callback);

    /**
     * @param callable|null $comparator function(IntValue $valueA, IntValue $valueB): int{-1, 0, 1}
     * @return IntsArray
     */
    public function unique(?callable $comparator = null);

    /**
     * @return int[]
     */
    public function toArray(): array;

    /**
     * @param callable $filter function(IntValue $value): bool
     * @return IntsArray
     */
    public function filter(callable $filter);

    /**
     * @return IntsArray
     */
    public function filterEmpty();

    /**
     * @param callable $transformer function(IntValue $value): IntValue|string
     * @return IntsArray
     */
    public function map(callable $transformer);

    /**
     * @param callable $transformer function(IntValue $value): iterable
     * @return IntsArray
     */
    public function flatMap(callable $transformer);

    /**
     * @param callable $reducer function(IntValue $value): string|int|bool
     * @return AssocValue AssocValue<IntsArray>
     */
    public function groupBy(callable $reducer): AssocValue;

    /**
     * @param callable $comparator function(IntValue $valueA, IntValue $valueB): int{-1, 0, 1}
     * @return IntsArray
     */
    public function sort(callable $comparator);

    /**
     * @return IntsArray
     */
    public function shuffle();

    /**
     * @return IntsArray
     */
    public function reverse();

    /**
     * @param IntValue|string $value
     * @return IntsArray
     */
    public function unshift($value);

    /**
     * @param mixed $value
     * @return IntsArray
     */
    public function shift(&$value = null);

    /**
     * @param IntValue|string $value
     * @return IntsArray
     */
    public function push($value);

    /**
     * @param mixed $value
     * @return IntsArray
     */
    public function pop(&$value = null);

    /**
     * @param int $offset
     */
    public function offsetExists($offset): bool;

    /**
     * @param int $offset
     * @return IntValue
     */
    public function offsetGet($offset);

    /**
     * @param int $offset
     * @param IntValue|string $value
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

    /**
     * @return IntsArray
     */
    public function join(ArrayValue $other);

    /**
     * @param callable $transformer function(mixed $reduced, IntValue $value): mixed
     * @param mixed $start
     * @return mixed
     */
    public function reduce(callable $transformer, $start);

    /**
     * @return IntsArray
     */
    public function notEmpty();

    /**
     * @return IntValue|null
     */
    public function first();

    /**
     * @return IntValue|null
     */
    public function last();

    /**
     * @param callable $filter function(IntValue $value): bool
     */
    public function find(callable $filter): ?IntValue;

    /**
     * @param callable $filter function(IntValue $value): bool
     */
    public function findLast(callable $filter): ?IntValue;

    /**
     * @return ArrayValue ArrayValue<IntValue>
     */
    public function toArrayValue(): ArrayValue;

    /**
     * @return AssocValue AssocValue<string, IntValue>
     */
    public function toAssocValue(): AssocValue;

    /**
     * @return IntsArray
     */
    public function sum();
}
