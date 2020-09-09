<?php declare(strict_types=1);

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
    /**
     * @phpstan-param callable(TValue $value, TKey $key=):void $callback
     * @phpstan-return AssocValue<TKey, TValue>
     */
    public function each(callable $callback): AssocValue;

    /**
     * @phpstan-param (callable(TValue $valueA, TValue $valueB):int)|null $comparator
     * @phpstan-return AssocValue<TKey, TValue>
     */
    public function unique(?callable $comparator = null): AssocValue;

    /**
     * @phpstan-param callable(TValue $value):bool $filter
     * @phpstan-return AssocValue<TKey, TValue>
     */
    public function filter(callable $filter): AssocValue;

    /**
     * @phpstan-return AssocValue<TKey, TValue>
     */
    public function filterEmpty(): AssocValue;

    /**
     * @template TNewValue
     * @param callable(TValue $value, TKey $key=):TNewValue $transformer
     * @phpstan-return AssocValue<TKey, TNewValue>
     */
    public function map(callable $transformer): AssocValue;

    /**
     * @param callable(TValue $valueA, TValue $valueB):int $comparator
     * @phpstan-return AssocValue<TKey, TValue>
     */
    public function sort(callable $comparator): AssocValue;

    /**
     * @phpstan-return AssocValue<TKey, TValue>
     */
    public function shuffle(): AssocValue;

    /**
     * @phpstan-return AssocValue<TKey, TValue>
     */
    public function reverse(): AssocValue;

    // ArrayAccess

    /**
     * @phpstan-param TKey $offset
     */
    public function offsetExists($offset): bool;

    /**
     * @phpstan-param TKey $offset
     * @return ?TValue
     */
    public function offsetGet($offset);

    /**
     * @phpstan-param TKey $offset
     * @phpstan-param TValue $value
     * @throws BadMethodCallException For immutable types.
     */
    public function offsetSet($offset, $value): void;

    /**
     * @phpstan-param TKey $offset
     * @throws BadMethodCallException For immutable types.
     */
    public function offsetUnset($offset): void;

    // AssocValue own

    /**
     * @phpstan-return array<TKey, TValue>
     */
    public function toAssocArray(): array;

    /**
     * @phpstan-return ArrayValue<TKey>
     */
    public function keys(): ArrayValue;

    /**
     * @phpstan-return ArrayValue<TValue>
     */
    public function values(): ArrayValue;

    /**
     * @template TNewKey
     * @phpstan-param callable(TKey $key, TValue $value=): TNewKey $transformer
     * @phpstan-return AssocValue<TNewKey, TValue>
     */
    public function mapKeys(callable $transformer): AssocValue;

    /**
     * @phpstan-param callable(TKey $keyA, TKey $keyB): int $comparator
     * @phpstan-return AssocValue<TKey, TValue>
     */
    public function sortKeys(callable $comparator): AssocValue;

    /**
     * @phpstan-param TKey $key
     * @phpstan-param TValue $value
     * @phpstan-return AssocValue<TKey, TValue>
     */
    public function with($key, $value): AssocValue;

    /**
     * @phpstan-param array<int, TKey> $keys
     * @phpstan-return AssocValue<TKey, TValue>
     */
    public function without(...$keys): AssocValue;

    /**
     * @phpstan-param array<int, TKey> $keys
     * @phpstan-return AssocValue<TKey, TValue>
     */
    public function only(...$keys): AssocValue;

    /**
     * @phpstan-param TValue $value
     * @phpstan-return AssocValue<TKey, TValue>
     */
    public function withoutElement($value): AssocValue;

    /**
     * @phpstan-param AssocValue<TKey, TValue> $other
     * @phpstan-return AssocValue<TKey, TValue>
     */
    public function merge(AssocValue $other): AssocValue;

    /**
     * @template TNewValue
     * @param callable(TNewValue $reduced, TValue $value, string $key):TNewValue $transformer
     * @phpstan-param TNewValue $start
     * @phpstan-return TNewValue
     */
    public function reduce(callable $transformer, $start);

    /**
     * @phpstan-param TKey $key
     * @phpstan-param ?TValue $default
     * @phpstan-return ?TValue
     */
    public function get($key, $default = null);

    /**
     * @phpstan-param TKey $key
     */
    public function has($key): bool;
}
