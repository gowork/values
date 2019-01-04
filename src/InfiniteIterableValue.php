<?php declare(strict_types=1);

namespace GW\Value;

final class InfiniteIterableValue implements IterableValue
{
    /** @var IterableValueStack */
    private $stack;

    public function __construct(iterable $iterable)
    {
        $this->stack = new IterableValueStack(new IterableValueIterator($iterable));
    }

    /**
     * @param callable $callback function(mixed $value): void
     * @return IterableValue
     */
    public function each(callable $callback)
    {
        foreach ($this->stack->iterate() as $value) {
            $callback($value);
        }

        return $this;
    }

    /**
     * @param callable|null $comparator function(mixed $valueA, mixed $valueB): int{-1, 0, 1}
     * @return IterableValue
     */
    public function unique(?callable $comparator = null)
    {
        if ($comparator === null) {
            $comparator = function ($a, $b) {
                return $a <=> $b;
            };
        }

        $knownValues = [];

        return $this->filter(
            function ($valueA) use (&$knownValues, $comparator) {
                foreach ($knownValues as $valueB) {
                    if ($comparator($valueA, $valueB) === 0) {
                        return false;
                    }
                }

                $knownValues[] = $valueA;

                return true;
            }
        );
    }

    /**
     * @return mixed[]
     */
    public function toArray(): array
    {
        $return = [];

        foreach ($this->stack->iterate() as $value) {
            $return[] = $value;
        }

        return $return;
    }

    /**
     * @param callable $filter function(mixed $value): bool { ... }
     */
    public function filter(callable $filter): InfiniteIterableValue
    {
        $clone = clone $this;
        $clone->stack = $clone->stack->push(function (iterable $iterable) use ($filter) {
            foreach ($iterable as $value) {
                if ($filter($value)) {
                    yield $value;
                }
            }
        });

        return $clone;
    }

    public function filterEmpty(): InfiniteIterableValue
    {
        return $this->filter(Filters::notEmpty());
    }

    /**
     * @param callable $transformer function(mixed $value): mixed { ... }
     * @return IterableValue
     */
    public function map(callable $transformer)
    {
        $clone = clone $this;
        $clone->stack = $clone->stack->push(function (iterable $iterable) use ($transformer) {
            foreach ($iterable as $value) {
                yield $transformer($value);
            }
        });

        return $clone;
    }

    /**
     * @param callable $transformer function(mixed $value): iterable { ... }
     * @return IterableValue
     */
    public function flatMap(callable $transformer)
    {
        $clone = clone $this;
        $clone->stack = $clone->stack->push(function (iterable $iterable) use ($transformer) {
            foreach ($iterable as $value) {
                yield from $transformer($value);
            }
        });

        return $clone;
    }

    public function toArrayValue(): ArrayValue
    {
        return Wrap::array($this->toArray());
    }

    /**
     * @param mixed $value
     * @return IterableValue
     */
    public function unshift($value)
    {
        $clone = clone $this;
        $clone->stack = $clone->stack->push(function (iterable $iterable) use ($value) {
            yield $value;
            yield from $iterable;
        });

        return $clone;
    }

    /**
     * @param mixed $value
     * @return IterableValue
     */
    public function push($value)
    {
        $clone = clone $this;
        $clone->stack = $clone->stack->push(function (iterable $iterable) use ($value) {
            yield from $iterable;
            yield $value;
        });

        return $clone;
    }

    /**
     * @return IterableValue
     */
    public function join(IterableValue $other)
    {
        $clone = clone $this;
        $clone->stack = $clone->stack->push(function (iterable $iterable) use ($other) {
            yield from $iterable;
            yield from $other;
        });

        return $clone;
    }

    /**
     * @return IterableValue
     */
    public function slice(int $offset, int $length)
    {
        $clone = clone $this;
        $clone->stack = $clone->stack->push(function (iterable $iterable) use ($offset, $length) {
            foreach ($iterable as $value) {
                if ($offset-- > 0) {
                    continue;
                }

                yield $value;

                if (--$length <= 0) {
                    break;
                }
            }
        });

        return $clone;
    }

    /**
     * @param callable|null $comparator function(mixed $valueA, mixed $valueB): int{-1, 0, 1}
     * @return IterableValue
     */
    public function diff(ArrayValue $other, ?callable $comparator = null)
    {
        $clone = clone $this;
        $clone->stack = $clone->stack->push(function (iterable $iterable) use ($other, $comparator) {
            foreach ($iterable as $value) {
                if ($comparator === null) {
                    $found = $other->hasElement($value);
                } else {
                    $found = $other->any(function($otherValue) use ($value, $comparator): bool {
                        return $comparator($otherValue, $value) === 0;
                    });
                }

                if ($found) {
                    continue;
                }

                yield $value;
            }
        });

        return $clone;
    }

    /**
     * @param callable|null $comparator function(mixed $valueA, mixed $valueB): int{-1, 0, 1}
     * @return IterableValue
     */
    public function intersect(ArrayValue $other, ?callable $comparator = null)
    {
        $clone = clone $this;
        $clone->stack = $clone->stack->push(function (iterable $iterable) use ($other, $comparator) {
            foreach ($iterable as $value) {
                if ($comparator === null) {
                    $found = $other->hasElement($value);
                } else {
                    $found = $other->any(function($otherValue) use ($value, $comparator): bool {
                        return $comparator($otherValue, $value) === 0;
                    });
                }

                if (!$found) {
                    continue;
                }

                yield $value;
            }
        });

        return $clone;
    }

    /**
     * @param callable $transformer function(mixed $reduced, mixed $value): mixed
     * @param mixed $start
     * @return mixed
     */
    public function reduce(callable $transformer, $start)
    {
        foreach ($this->stack->iterate() as $value) {
            $start = $transformer($start, $value);
        }

        return $start;
    }

    /**
     * @param callable $filter function(mixed $value): bool
     * @return mixed
     */
    public function find(callable $filter)
    {
        foreach ($this->stack->iterate() as $item) {
            if ($filter($item)) {
                return $item;
            }
        }

        return null;
    }

    /**
     * @param callable $filter function(mixed $value): bool
     * @return mixed
     */
    public function findLast(callable $filter)
    {
        $last = null;

        foreach ($this->stack->iterate() as $item) {
            if ($filter($item)) {
                $last = $item;
            }
        }

        return $last;
    }

    public function any(callable $filter): bool
    {
        foreach ($this->stack->iterate() as $value) {
            if ($filter($value)) {
                return true;
            }
        }

        return false;
    }

    public function every(callable $filter): bool
    {
        foreach ($this->stack->iterate() as $value) {
            if (!$filter($value)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return IterableValue
     */
    public function chunk(int $size)
    {
        $clone = clone $this;
        $clone->stack = $this->stack->push(function (iterable $iterable) use ($size) {
            $buffer = [];

            foreach ($iterable as $item) {
                $buffer[] = $item;

                if (\count($buffer) === $size) {
                    yield $buffer;
                    $buffer = [];
                }
            }

            if ($buffer !== []) {
                yield $buffer;
            }
        });

        return $clone;
    }

    /**
     * @return IterableValue
     */
    public function flatten(): IterableValue
    {
        $clone = clone $this;
        $clone->stack = $this->stack->push(function (iterable $iterable) {
            foreach ($iterable as $item) {
                yield from $item;
            }
        });

        return $clone;
    }

    /**
     * @return IterableValue
     */
    public function notEmpty()
    {
        return $this->filter(Filters::notEmpty());
    }

    /**
     * @return mixed
     */
    public function first()
    {
        foreach ($this->stack->iterate() as $value) {
            return $value;
        }

        return null;
    }

    /**
     * @return mixed
     */
    public function last()
    {
        $value = null;
        foreach ($this->stack->iterate() as $value) {}

        return $value;
    }

    public function getIterator(): \Traversable
    {
        yield from $this->stack->iterate();
    }

    public function use(iterable $iterable): IterableValue
    {
        $clone = clone $this;
        $clone->stack = $this->stack->replaceIterator(new IterableValueIterator($iterable));

        return $clone;
    }
}
