<?php

namespace GW\Value;

final class PlainArray implements ArrayValue
{
    /** @var array */
    private $items;

    public function __construct(array $items)
    {
        $this->items = array_values($items);
    }

    /**
     * @param callable $transformer function(mixed $value): mixed
     */
    public function map(callable $transformer): PlainArray
    {
        return new self(array_map($transformer, $this->items));
    }

    public function filterEmpty(): PlainArray
    {
        return $this->filter(Filters::notEmpty());
    }

    /**
     * @param callable $transformer function(mixed $value): bool
     */
    public function filter(callable $transformer): PlainArray
    {
        return new self(array_filter($this->items, $transformer));
    }

    /**
     * @param callable $comparator function(mixed $leftValue, mixed $rightValue): int{-1, 0, 1}
     */
    public function sort(callable $comparator): PlainArray
    {
        $items = $this->items;
        uasort($items, $comparator);

        return new self($items);
    }

    /**
     * @param callable $callback function(mixed $value): void
     */
    public function each(callable $callback): PlainArray
    {
        foreach ($this->items as $item) {
            $callback($item);
        }

        return $this;
    }

    public function reverse(): PlainArray
    {
        return new self(array_reverse($this->items, false));
    }

    public function join(ArrayValue $other): PlainArray
    {
        return new self(array_merge($this->items, $other->toArray()));
    }

    public function slice(int $offset, int $length): PlainArray
    {
        // TODO require valid values or just leave to php?
        return new self(array_slice($this->items, $offset, $length));
    }

    /**
     * @param callable|null $comparator function(mixed $valueA, mixed $valueB): int{-1, 0, 1}
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
     * @param callable|null $comparator function(mixed $valueA, mixed $valueB): int{-1, 0, 1}
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
     * @param callable|null $comparator function(mixed $valueA, mixed $valueB): int{-1, 0, 1}
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

    public function shuffle(): PlainArray
    {
        $items = $this->items;
        shuffle($items);

        return new self($items);
    }

    // adders and removers

    /**
     * @param mixed $value
     */
    public function unshift($value): PlainArray
    {
        $clone = clone $this;
        array_unshift($clone->items, $value);

        return $clone;
    }

    /**
     * @param mixed $value
     */
    public function shift(&$value = null): PlainArray
    {
        $clone = clone $this;
        $value = array_shift($clone->items);

        return $clone;
    }

    /**
     * @param mixed $value
     */
    public function push($value): PlainArray
    {
        $clone = clone $this;
        array_push($clone->items, $value);

        return $clone;
    }

    /**
     * @param mixed $value
     */
    public function pop(&$value = null): PlainArray
    {
        $clone = clone $this;
        $value = array_pop($clone->items);

        return $clone;
    }

    // finalizers

    /**
     * @param callable $transformer function(mixed $reduced, mixed $value): mixed
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
    public function first()
    {
        return $this->items[0] ?? null;
    }

    /**
     * @return mixed
     */
    public function last()
    {
        $count = $this->count();

        return $count > 0 ? $this->items[$count - 1] : null;
    }

    public function count(): int
    {
        return count($this->items);
    }

    /**
     * @return mixed[]
     */
    public function toArray(): array
    {
        return $this->items;
    }

    public function getIterator(): \Iterator
    {
        return new \ArrayIterator($this->items);
    }

    public function offsetExists($offset): bool
    {
        // TODO require int offset
        return isset($this->items[$offset]);
    }

    /**
     * @param int $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        // TODO require int offset
        return $this->items[$offset];
    }

    public function offsetSet($offset, $value)
    {
        // TODO more proper exception
        throw new \BadMethodCallException('ArrayValue is immutable');
    }

    public function offsetUnset($offset)
    {
        // TODO more proper exception
        throw new \BadMethodCallException('ArrayValue is immutable');
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

    public function toAssocValue(): AssocValue
    {
        return Wrap::assocArray($this->items);
    }
}
