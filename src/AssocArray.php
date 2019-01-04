<?php

namespace GW\Value;

final class AssocArray implements AssocValue
{
    /** @var array */
    private $items;

    public function __construct(array $items)
    {
        $this->items = $items;
    }

    /**
     * @param callable $transformer function(mixed $value[, string $key]): mixed
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
     * @return StringsArray
     */
    public function keys(): StringsArray
    {
        return Wrap::stringsArray(array_keys($this->items));
    }

    /**
     * @param callable $transformer function(string $key[, mixed $value]): string
     */
    public function mapKeys(callable $transformer): AssocArray
    {
        return new self(array_combine(array_map($transformer, array_keys($this->items), $this->items), $this->items));
    }

    public function filterEmpty(): AssocArray
    {
        return $this->filter(Filters::notEmpty());
    }

    public function filter(callable $filter): AssocArray
    {
        return new self(array_filter($this->items, $filter));
    }

    public function sort(callable $comparator): AssocArray
    {
        $items = $this->items;
        uasort($items, $comparator);

        return new self($items);
    }

    public function reverse(): AssocArray
    {
        return new self(array_reverse($this->items, true));
    }

    public function shuffle(): AssocArray
    {
        $items = $this->items;
        shuffle($items);

        return new self($items);
    }

    /**
     * @param callable $comparator function(string $keyA, string $keyB): int{-1, 1}
     */
    public function sortKeys(callable $comparator): AssocArray
    {
        $items = $this->items;
        uksort($items, $comparator);

        return new self($items);
    }

    /**
     * @param callable $callback function(mixed $value, string $key): void
     */
    public function each(callable $callback): AssocArray
    {
        foreach ($this->items as $key => $item) {
            $callback($item, $key);
        }

        return $this;
    }

    /**
     * @param callable|null $comparator function(mixed $valueA, mixed $valueB): int{-1, 0, 1}
     */
    public function unique(?callable $comparator = null): AssocArray
    {
        if ($comparator === null) {
            return new self(array_unique($this->items));
        }

        return $this->reduce(
            function (self $unique, $item, string $key) use ($comparator): self {
                return $unique->hasComparable($item, $comparator) ? $unique : $unique->with($key, $item);
            },
            new self([])
        );
    }

    /**
     * @param mixed $value
     */
    public function with(string $key, $value): AssocArray
    {
        return $this->merge(new self([$key => $value]));
    }

    public function merge(AssocValue $other): AssocArray
    {
        return new self(array_merge($this->items, $other->toAssocArray()));
    }

    public function without(string ...$keys): AssocArray
    {
        return new self(array_diff_key($this->items, array_flip($keys)));
    }

    public function only(string ...$keys): AssocArray
    {
        return new self(array_intersect_key($this->items, array_flip($keys)));
    }

    /**
     * @param mixed $value
     */
    public function withoutElement($value): AssocArray
    {
        return $this->filter(Filters::notEqual($value));
    }

    public function reduce(callable $transformer, $start)
    {
        $reduced = $start;

        foreach ($this->items as $key => $value) {
            $reduced = $transformer($reduced, $value, $key);
        }

        return $reduced;
    }

    /**
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        return $this->items[$key] ?? $default;
    }

    public function has(string $key): bool
    {
        return isset($this->items[$key]);
    }

    /**
     * @return mixed
     */
    public function first()
    {
        return $this->values()->first();
    }

    public function values(): ArrayValue
    {
        return Wrap::array($this->items);
    }

    /**
     * @return mixed
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
     * @return mixed
     */
    public function findLast(callable $filter)
    {
        return $this->values()->findLast($filter);
    }

    public function hasElement($element): bool
    {
        return \in_array($element, $this->items, true);
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
     * @return mixed[]
     */
    public function toArray(): array
    {
        return $this->values()->toArray();
    }

    public function toAssocArray(): array
    {
        return $this->items;
    }

    public function getIterator(): \Iterator
    {
        return new \ArrayIterator($this->items);
    }

    public function count(): int
    {
        return \count($this->items);
    }

    public function isEmpty(): bool
    {
        return $this->items === [];
    }

    public function offsetExists($offset): bool
    {
        return isset($this->items[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->items[$offset];
    }

    public function offsetSet($offset, $value): void
    {
        throw new \BadMethodCallException('AssocArray is immutable');
    }

    public function offsetUnset($offset): void
    {
        throw new \BadMethodCallException('AssocArray is immutable');
    }

    private function hasComparable($other, callable $comparator): bool
    {
        return $this->any(function ($item) use ($other, $comparator): bool {
            return $comparator($item, $other) === 0;
        });
    }
}
