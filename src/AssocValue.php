<?php

namespace GW\Value;

use BadMethodCallException;
use IteratorAggregate;
use ArrayAccess;

/**
 * @template TKey
 * @template TValue
 * @extends Collection<TValue>
 * @extends IteratorAggregate<TKey, TValue>
 * @extends ArrayAccess<TKey, TValue>
 */
interface AssocValue extends Value, Collection, IteratorAggregate, ArrayAccess
{
    // Collection

    /**
     * @param callable(TValue $value):void $callback
     * @return AssocValue<TKey, TValue>
     */
    public function each(callable $callback): AssocValue;

    /**
     * @param callable(TValue $valueA, TValue $valueB):int|null $comparator
     * @return AssocValue<TKey, TValue>
     */
    public function unique(?callable $comparator = null): AssocValue;

    /**
     * @param callable(TValue $value):bool $filter
     * @return AssocValue<TKey, TValue>
     */
    public function filter(callable $filter): AssocValue;

    /**
     * @return AssocValue<TKey, TValue>
     */
    public function filterEmpty(): AssocValue;

    /**
     * @template TNewValue
     * @param callable(TValue $value, TKey $key=):TNewValue $transformer
     * @return AssocValue<TKey, TNewValue>
     */
    public function map(callable $transformer): AssocValue;

    /**
     * @param callable(TValue $valueA, TValue $valueB):int $comparator
     * @return AssocValue<TKey, TValue>
     */
    public function sort(callable $comparator): AssocValue;

    /**
     * @return AssocValue<TKey, TValue>
     */
    public function shuffle(): AssocValue;

    /**
     * @return AssocValue<TKey, TValue>
     */
    public function reverse(): AssocValue;

    // ArrayAccess

    /**
     * @param TKey $offset
     */
    public function offsetExists($offset): bool;

    /**
     * @param TKey $offset
     * @return ?TValue
     */
    public function offsetGet($offset);

    /**
     * @param TKey $offset
     * @param TValue $value
     * @throws BadMethodCallException For immutable types.
     */
    public function offsetSet($offset, $value): void;

    /**
     * @param TKey $offset
     * @throws BadMethodCallException For immutable types.
     */
    public function offsetUnset($offset): void;

    // AssocValue own

    /**
     * @return array<TKey, TValue>
     */
    public function toAssocArray(): array;

    /**
     * @return ArrayValue<TKey>
     */
    public function keys(): ArrayValue;

    /**
     * @return ArrayValue<TValue>
     */
    public function values(): ArrayValue;

    /**
     * @template TNewKey
     * @param callable(TKey $key, TValue $value=): TNewKey $transformer
     * @return AssocValue<TNewKey, TValue>
     */
    public function mapKeys(callable $transformer): AssocValue;

    /**
     * @param callable(TKey $keyA, TKey $keyB): int $comparator
     * @return AssocValue<TKey, TValue>
     */
    public function sortKeys(callable $comparator): AssocValue;

    /**
     * @param TKey $key
     * @param TValue $value
     * @return AssocValue<TKey, TValue>
     */
    public function with($key, $value): AssocValue;

    /**
     * @param TKey[] $keys
     * @return AssocValue<TKey, TValue>
     */
    public function without(...$keys): AssocValue;

    /**
     * @param TKey[] $keys
     * @return AssocValue<TKey, TValue>
     */
    public function only(...$keys): AssocValue;

    /**
     * @param TValue $value
     * @return AssocValue<TKey, TValue>
     */
    public function withoutElement($value): AssocValue;

    /**
     * @param AssocValue<TKey, TValue> $other
     * @return AssocValue<TKey, TValue>
     */
    public function merge(AssocValue $other): AssocValue;

    /**
     * @template TNewValue
     * @param callable(TNewValue $reduced, TValue $value, string $key):TNewValue $transformer
     * @param TNewValue $start
     * @return TNewValue
     */
    public function reduce(callable $transformer, $start);

    /**
     * @param TKey $key
     * @param ?TValue $default
     * @return ?TValue
     */
    public function get($key, $default = null);

    /**
     * @param TKey $key
     */
    public function has($key): bool;
}
