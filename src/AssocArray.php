<?php

namespace GW\Value;

use RuntimeException;
use ArrayIterator;
use BadMethodCallException;
use function in_array;
use function count;

/**
 * @template TKey
 * @template TValue
 * @implements AssocValue<TKey, TValue>
 */
final class AssocArray implements AssocValue
{
    /** @var array<TKey, TValue> */
    private array $items;

    /**
     * @param array<TKey, TValue> $items
     */
    public function __construct(array $items)
    {
        $this->items = $items;
    }

    /**
     * @template TNewValue
     * @param callable(TValue $value, TKey $key=):TNewValue $transformer
     * @return AssocArray<TKey, TNewValue>
     */
    public function map(callable $transformer): AssocArray
    {
        $result = [];
        foreach ($this->items as $key => $value) {
            $result[$key] = $transformer($value, $key);
        }

        return new self($result);
    }

    /**
     * @return ArrayValue<TKey>
     */
    public function keys(): ArrayValue
    {
        return Wrap::array(array_keys($this->items));
    }

    /**
     * @template TNewKey
     * @param callable(TKey $key, TValue $value=): TNewKey $transformer
     * @return AssocArray<TNewKey, TValue>
     */
    public function mapKeys(callable $transformer): AssocArray
    {
        $combined = array_combine(array_map($transformer, array_keys($this->items), $this->items), $this->items);

        if ($combined === false) {
            throw new RuntimeException('Cannot map keys - combined array is broken.');
        }

        return new self($combined);
    }

    /**
     * @return AssocArray<TKey, TValue>
     */
    public function filterEmpty(): AssocArray
    {
        return $this->filter(Filters::notEmpty());
    }

    /**
     * @param callable(TValue $value):bool $filter
     * @return AssocArray<TKey, TValue>
     */
    public function filter(callable $filter): AssocArray
    {
        return new self(array_filter($this->items, $filter));
    }

    /**
     * @param callable(TValue $valueA, TValue $valueB):int $comparator
     * @return AssocArray<TKey, TValue>
     */
    public function sort(callable $comparator): AssocArray
    {
        $items = $this->items;
        uasort($items, $comparator);

        return new self($items);
    }

    /**
     * @return AssocArray<TKey, TValue>
     */
    public function reverse(): AssocArray
    {
        return new self(array_reverse($this->items, true));
    }

    /**
     * @return AssocArray<TKey, TValue>
     */
    public function shuffle(): AssocArray
    {
        $items = $this->items;
        shuffle($items);

        return new self($items);
    }

    /**
     * @param callable(TKey $keyA, TKey $keyB): int $comparator
     * @return AssocArray<TKey, TValue>
     */
    public function sortKeys(callable $comparator): AssocArray
    {
        $items = $this->items;
        uksort($items, $comparator);

        return new self($items);
    }

    /**
     * @param callable(TValue $value):void $callback
     * @return AssocArray<TKey, TValue>
     */
    public function each(callable $callback): AssocArray
    {
        foreach ($this->items as $key => $item) {
            $callback($item, $key);
        }

        return $this;
    }

    /**
     * @param callable(TValue $valueA, TValue $valueB):int|null $comparator
     * @return AssocArray<TKey, TValue>
     */
    public function unique(?callable $comparator = null): AssocArray
    {
        if ($comparator === null) {
            return new self(array_unique($this->items));
        }

        $result = [];
        foreach ($this->items as $keyA => $valueA) {
            foreach ($result as $valueB) {
                if ($comparator($valueA, $valueB) === 0) {
                    // item already in result
                    continue 2;
                }
            }

            $result[$keyA] = $valueA;
        }

        return new self($result);
    }

    /**
     * @param TKey $value
     * @return AssocArray<TKey, TValue>
     */
    public function with($key, $value): AssocArray
    {
        return $this->merge(new self([$key => $value]));
    }

    /**
     * @param AssocValue<TKey, TValue> $other
     * @return AssocArray<TKey, TValue>
     */
    public function merge(AssocValue $other): AssocArray
    {
        return new self(array_merge($this->items, $other->toAssocArray()));
    }

    /**
     * @param TKey[] $keys
     * @return AssocArray<TKey, TValue>
     */
    public function without(...$keys): AssocArray
    {
        return new self(array_diff_key($this->items, array_flip($keys)));
    }

    /**
     * @param TKey[] $keys
     * @return AssocArray<TKey, TValue>
     */
    public function only(...$keys): AssocArray
    {
        return new self(array_intersect_key($this->items, array_flip($keys)));
    }

    /**
     * @param TValue $value
     * @return AssocArray<TKey, TValue>
     */
    public function withoutElement($value): AssocArray
    {
        return $this->filter(Filters::notEqual($value));
    }

    /**
     * @template TNewValue
     * @param callable(TNewValue $reduced, TValue $value, string $key):TNewValue $transformer
     * @param TNewValue $start
     * @return TNewValue
     */
    public function reduce(callable $transformer, $start)
    {
        $reduced = $start;

        foreach ($this->items as $key => $value) {
            $reduced = $transformer($reduced, $value, $key);
        }

        return $reduced;
    }

    /**
     * @param TKey $key
     * @param ?TValue $default
     * @return ?TValue
     */
    public function get($key, $default = null)
    {
        return $this->items[$key] ?? $default;
    }

    /**
     * @param TKey $key
     */
    public function has($key): bool
    {
        return isset($this->items[$key]);
    }

    /**
     * @return ?TValue
     */
    public function first()
    {
        return $this->values()->first();
    }

    /**
     * @return ArrayValue<TValue>
     */
    public function values(): ArrayValue
    {
        return Wrap::array($this->items);
    }

    /**
     * @return ?TValue
     */
    public function last()
    {
        return $this->values()->last();
    }

    /**
     * @param callable $filter function(mixed $value): bool
     * @return mixed
     */
    public function find(callable $filter)
    {
        return $this->values()->find($filter);
    }

    /**
     * @param callable $filter function(mixed $value): bool
     * @return ?TValue
     */
    public function findLast(callable $filter)
    {
        return $this->values()->findLast($filter);
    }

    /**
     * @param TValue $element
     */
    public function hasElement($element): bool
    {
        return in_array($element, $this->items, true);
    }

    public function any(callable $filter): bool
    {
        return $this->values()->any($filter);
    }

    public function every(callable $filter): bool
    {
        return $this->values()->every($filter);
    }

    /**
     * @return TValue[]
     */
    public function toArray(): array
    {
        return $this->values()->toArray();
    }

    /**
     * @return array<TKey, TValue>
     */
    public function toAssocArray(): array
    {
        return $this->items;
    }

    /**
     * @return ArrayIterator<TKey, TValue>
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->items);
    }

    public function count(): int
    {
        return count($this->items);
    }

    public function isEmpty(): bool
    {
        return $this->items === [];
    }

    /**
     * @param TKey $offset
     */
    public function offsetExists($offset): bool
    {
        return isset($this->items[$offset]);
    }

    /**
     * @param TKey $offset
     * @return ?TValue
     */
    public function offsetGet($offset)
    {
        return $this->items[$offset];
    }

    public function offsetSet($offset, $value): void
    {
        throw new BadMethodCallException('AssocArray is immutable');
    }

    public function offsetUnset($offset): void
    {
        throw new BadMethodCallException('AssocArray is immutable');
    }
}
