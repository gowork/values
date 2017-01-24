<?php

namespace GW\Value;

final class AssocArray implements Value, Collection, \IteratorAggregate
{
    /** @var array */
    private $items;

    /** @var string[] */
    private $keys;

    public function __construct(array $items)
    {
        $this->items = $this->transformArrayKeys($items, 'strval');
        $this->keys = array_keys($this->items);
    }

    private function transformArrayKeys(array $items, callable $transformer): array
    {
        return array_combine(array_map($transformer, array_keys($items)), $items);
    }

    /**
     * @param callable $transformer function(mixed $value, string $key): mixed
     */
    public function map(callable $transformer): AssocArray
    {
        return new self(array_map($transformer, $this->items, $this->keys));
    }

    /**
     * @return ArrayValue|string[]
     */
    public function keys(): ArrayValue
    {
        return Arrays::create($this->keys);
    }

    /**
     * @param callable $transformer function(string $key): string
     */
    public function mapKeys(callable $transformer): AssocArray
    {
        return new self($this->transformArrayKeys($this->items, $transformer));
    }

    public function filterEmpty(): AssocArray
    {
        return $this->filter(Filters::notEmpty());
    }

    public function filter(callable $transformer): AssocArray
    {
        return new self(array_filter($this->items, $transformer));
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
     * @param mixed $value
     */
    public function with(string $key, $value): AssocArray
    {
        return $this->merge(new self([$key => $value]));
    }

    public function merge(AssocArray $other): AssocArray
    {
        return new self(array_merge($this->items, $other->items));
    }

    // finalizers

    public function without(string $key): AssocArray
    {
        $items = $this->items;
        unset($items[$key]);

        return new self($items);
    }

    /**
     * @param mixed $value
     */
    public function withoutElement($value): AssocArray
    {
        return $this->filter(Filters::noteEqual($value));
    }

    /**
     * @param callable $transformer function(mixed $value, mixed $reduced, string $key): mixed
     * @param mixed $start
     * @return mixed
     */
    public function reduce(callable $transformer, $start)
    {
        return array_reduce($this->items, $transformer, $start);
    }

    /**
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        // TODO what if does not exist?
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
        return Arrays::create($this->items);
    }

    /**
     * @return mixed
     */
    public function last()
    {
        return $this->values()->last();
    }

    /**
     * @return mixed[]
     */
    public function toArray(): array
    {
        return $this->values()->toArray();
    }

    public function getIterator(): \Iterator
    {
        return new \ArrayIterator($this->items);
    }

    public function count(): int
    {
        return count($this->items);
    }

    public function isEmpty(): bool
    {
        return $this->items === [];
    }
}
