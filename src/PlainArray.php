<?php

namespace GW\Value;

use ArrayIterator;
use BadMethodCallException;
use function array_chunk;
use function array_reverse;
use function array_slice;
use function array_splice;
use function array_map;
use function array_merge;
use function count;
use function in_array;
use function is_array;
use function is_bool;
use function iterator_to_array;

/**
 * @template TValue
 * @implements ArrayValue<TValue>
 */
final class PlainArray implements ArrayValue
{
    /** @phpstan-var array<int, TValue> */
    private array $items;

    /**
     * @phpstan-param array<mixed, TValue> $items
     */
    public function __construct(array $items)
    {
        $this->items = array_values($items);
    }

    /**
     * @template TNewValue
     * @param callable(TValue $value):TNewValue $transformer
     * @return PlainArray<TNewValue>
     */
    public function map(callable $transformer): PlainArray
    {
        return new self(array_map($transformer, $this->items));
    }

    /**
     * @template TNewValue
     * @param callable(TValue $value):iterable<TNewValue> $transformer
     * @return PlainArray<TNewValue>
     */
    public function flatMap(callable $transformer): PlainArray
    {
        $elements = [];

        foreach ($this->items as $item) {
            $transformed = $transformer($item);
            $elements[] = is_array($transformed) ? $transformed : iterator_to_array($transformed);
        }

        return new self(array_merge([], ...$elements));
    }

    /**
     * @template TNewKey
     * @phpstan-param callable(TValue $value):TNewKey $reducer
     * @phpstan-return AssocValue<TNewKey, ArrayValue<TValue>>
     */
    public function groupBy(callable $reducer): AssocValue
    {
        /** @phpstan-var array<TNewKey, ArrayValue<TValue>> $groups */
        $groups = [];

        /** @phpstan-var ArrayValue<TValue> $empty */
        $empty = Wrap::array([]);

        foreach ($this->items as $item) {
            /** @phpstan-var TNewKey $key */
            $key = $reducer($item);
            $groups[$key] = ($groups[$key] ?? $empty)->push($item);
        }

        /** @phpstan-var array<TNewKey, ArrayValue<TValue>> $groups */
        return Wrap::assocArray($groups);
    }

    /**
     * @phpstan-return PlainArray<array<TValue>>
     */
    public function chunk(int $size): PlainArray
    {
        return new self(array_chunk($this->items, $size, false));
    }

    /**
     * @phpstan-return PlainArray<TValue>
     */
    public function filterEmpty(): PlainArray
    {
        return $this->filter(Filters::notEmpty());
    }

    /**
     * @param callable(TValue $value):bool $filter
     * @return PlainArray<TValue>
     */
    public function filter(callable $filter): PlainArray
    {
        return new self(array_filter($this->items, $filter));
    }

    /**
     * @param callable(TValue $leftValue, TValue $rightValue):int $comparator
     * @return PlainArray<TValue>
     */
    public function sort(callable $comparator): PlainArray
    {
        $items = $this->items;
        uasort($items, $comparator);

        return new self($items);
    }

    /**
     * @param callable(TValue $value):void $callback
     * @return PlainArray<TValue>
     */
    public function each(callable $callback): PlainArray
    {
        foreach ($this->items as $item) {
            $callback($item);
        }

        return $this;
    }

    /**
     * @return PlainArray<TValue>
     */
    public function reverse(): PlainArray
    {
        return new self(array_reverse($this->items, false));
    }

    /**
     * @return PlainArray<TValue>
     */
    public function join(ArrayValue $other): PlainArray
    {
        return new self(array_merge($this->items, $other->toArray()));
    }

    /**
     * @return PlainArray<TValue>
     */
    public function slice(int $offset, int $length): PlainArray
    {
        return new self(array_slice($this->items, $offset, $length));
    }

    /**
     * @param ArrayValue<TValue> $replacement
     * @return PlainArray<TValue>
     */
    public function splice(int $offset, int $length, ?ArrayValue $replacement = null): PlainArray
    {
        $items = $this->items;
        array_splice($items, $offset, $length, $replacement !== null ? $replacement->toArray() : []);

        return new self($items);
    }

    /**
     * @param callable(TValue $valueA, TValue $valueB):int | null $comparator
     * @return PlainArray<TValue>
     */
    public function unique(?callable $comparator = null): PlainArray
    {
        if ($comparator === null) {
            return new self(array_unique($this->items));
        }

        $result = [];

        foreach ($this->items as $valueA) {
            foreach ($result as $valueB) {
                if ($comparator($valueA, $valueB) === 0) {
                    // item already in result
                    continue 2;
                }
            }

            $result[] = $valueA;
        }

        return new self($result);
    }

    /**
     * @param ArrayValue<TValue> $other
     * @param callable(TValue $valueA, TValue $valueB):int | null $comparator
     * @return PlainArray<TValue>
     */
    public function diff(ArrayValue $other, ?callable $comparator = null): PlainArray
    {
        if ($other->count() === 0) {
            return $this;
        }

        if ($comparator === null) {
            return new self(array_diff($this->items, $other->toArray()));
        }

        return new self(array_udiff($this->items, $other->toArray(), $comparator));
    }

    /**
     * @param ArrayValue<TValue> $other
     * @param callable(TValue $valueA, TValue $valueB):int | null $comparator
     * @return PlainArray<TValue>
     */
    public function intersect(ArrayValue $other, ?callable $comparator = null): PlainArray
    {
        if ($this->items === $other->toArray()) {
            return $this;
        }

        if ($comparator === null) {
            return new self(array_intersect($this->items, $other->toArray()));
        }

        return new self(array_uintersect($this->items, $other->toArray(), $comparator));
    }

    /**
     * @return PlainArray<TValue>
     */
    public function shuffle(): PlainArray
    {
        $items = $this->items;
        shuffle($items);

        return new self($items);
    }

    // adders and removers

    /**
     * @param TValue $value
     * @return PlainArray<TValue>
     */
    public function unshift($value): PlainArray
    {
        $clone = clone $this;
        array_unshift($clone->items, $value);

        return $clone;
    }

    /**
     * @param ?TValue $value
     * @return PlainArray<TValue>
     */
    public function shift(&$value = null): PlainArray
    {
        $clone = clone $this;
        $value = array_shift($clone->items);

        return $clone;
    }

    /**
     * @param TValue $value
     * @return PlainArray<TValue>
     */
    public function push($value): PlainArray
    {
        $clone = clone $this;
        $clone->items[] = $value;

        return $clone;
    }

    /**
     * @param TValue|null $value
     * @return PlainArray<TValue>
     */
    public function pop(&$value = null): PlainArray
    {
        $clone = clone $this;
        $value = array_pop($clone->items);

        return $clone;
    }

    // finalizers

    /**
     * @template TNewValue
     * @param callable(TNewValue $reduced, TValue $value):TNewValue $transformer
     * @param TNewValue $start
     * @return TNewValue
     */
    public function reduce(callable $transformer, $start)
    {
        return array_reduce($this->items, $transformer, $start);
    }

    /**
     * @return ?TValue
     */
    public function first()
    {
        return $this->items[0] ?? null;
    }

    /**
     * @return ?TValue
     */
    public function last()
    {
        $count = $this->count();

        return $count > 0 ? $this->items[$count - 1] : null;
    }

    /**
     * @param callable(TValue $value):bool $filter
     * @return ?TValue
     */
    public function find(callable $filter)
    {
        foreach ($this->items as $item) {
            if ($filter($item)) {
                return $item;
            }
        }

        return null;
    }

    /**
     * @param callable(TValue $value):bool $filter
     * @return ?TValue
     */
    public function findLast(callable $filter)
    {
        foreach (array_reverse($this->items) as $item) {
            if ($filter($item)) {
                return $item;
            }
        }

        return null;
    }

    public function hasElement($element): bool
    {
        return in_array($element, $this->items, true);
    }

    /**
     * @param callable(TValue $value):bool $filter
     */
    public function any(callable $filter): bool
    {
        foreach ($this->items as $item) {
            if ($filter($item)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param callable(TValue $value):bool $filter
     */
    public function every(callable $filter): bool
    {
        foreach ($this->items as $item) {
            if (!$filter($item)) {
                return false;
            }
        }

        return true;
    }

    public function count(): int
    {
        return count($this->items);
    }

    /**
     * @return TValue[]
     */
    public function toArray(): array
    {
        return $this->items;
    }

    /**
     * @return ArrayIterator<int, TValue>
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->items);
    }

    /**
     * @param int $offset
     */
    public function offsetExists($offset): bool
    {
        return isset($this->items[$offset]);
    }

    /**
     * @param int $offset
     * @return ?TValue
     */
    public function offsetGet($offset)
    {
        return $this->items[$offset];
    }

    /**
     * @param int $offset
     * @param TValue $value
     * @throws BadMethodCallException For immutable types.
     */
    public function offsetSet($offset, $value): void
    {
        throw new BadMethodCallException('ArrayValue is immutable');
    }

    /**
     * @param int $offset
     * @throws BadMethodCallException For immutable types.
     */
    public function offsetUnset($offset): void
    {
        throw new BadMethodCallException('ArrayValue is immutable');
    }

    public function implode(string $glue): StringValue
    {
        return Wrap::string(implode($glue, $this->toArray()));
    }

    public function notEmpty(): PlainArray
    {
        return $this->filter(Filters::notEmpty());
    }

    public function isEmpty(): bool
    {
        return $this->items === [];
    }

    /**
     * @return AssocValue<int, TValue>
     */
    public function toAssocValue(): AssocValue
    {
        return Wrap::assocArray($this->items);
    }

    public function toStringsArray(): StringsArray
    {
        return Wrap::stringsArray($this->items);
    }
}
