<?php declare(strict_types=1);

namespace GW\Value;

final class InfiniteIterableValue implements IterableValue
{
    /** @var IterableValueIterator */
    private $rootIterator;

    /** @var object&callable */
    private $iterator;

    public function __construct(iterable $iterable)
    {
        $this->iterator = new IterableValueIterator($iterable);
        $this->rootIterator = $this->iterator;
    }

    /**
     * @param callable $callback function(mixed $value): void
     * @return IterableValue
     */
    public function each(callable $callback)
    {
        foreach (($this->iterator)() as $value) {
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

        foreach (($this->iterator)() as $value) {
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
        $clone->iterator = function () use ($filter) {
            foreach (($this->iterator)() as $value) {
                if ($filter($value)) {
                    yield $value;
                }
            }
        };

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
        $clone->iterator = function () use ($transformer) {
            foreach (($this->iterator)() as $value) {
                yield $transformer($value);
            }
        };

        return $clone;
    }

    /**
     * @param callable $transformer function(mixed $value): iterable { ... }
     * @return IterableValue
     */
    public function flatMap(callable $transformer)
    {
        $clone = clone $this;
        $clone->iterator = function () use ($transformer) {
            foreach (($this->iterator)() as $value) {
                yield from $transformer($value);
            }
        };

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
        $clone->iterator = function () use ($value) {
            yield $value;
            yield from ($this->iterator)();
        };

        return $clone;
    }

    /**
     * @param mixed $value
     * @return IterableValue
     */
    public function push($value)
    {
        $clone = clone $this;
        $clone->iterator = function () use ($value) {
            yield from ($this->iterator)();
            yield $value;
        };

        return $clone;
    }

    /**
     * @return IterableValue
     */
    public function join(IterableValue $other)
    {
        $clone = clone $this;
        $clone->iterator = function () use ($other) {
            yield from ($this->iterator)();
            yield from $other;
        };

        return $clone;
    }

    /**
     * @return IterableValue
     */
    public function slice(int $offset, int $length)
    {
        $clone = clone $this;
        $clone->iterator = function () use ($offset, $length) {
            foreach (($this->iterator)() as $value) {
                if ($offset-- > 0) {
                    continue;
                }

                if ($length-- <= 0) {
                    break;
                }

                yield $value;
            }
        };

        return $clone;
    }

    /**
     * @param callable|null $comparator function(mixed $valueA, mixed $valueB): int{-1, 0, 1}
     * @return IterableValue
     */
    public function diff(ArrayValue $other, ?callable $comparator = null)
    {
        $clone = clone $this;
        $clone->iterator = function () use ($other, $comparator) {
            $otherValues = $other->toArray();

            foreach (($this->iterator)() as $value) {
                if ($comparator === null) {
                    $found = \in_array($value, $otherValues, true);
                } else {
                    $found = false;
                    foreach ($otherValues as $otherValue) {
                        if ($comparator($otherValue, $value) === 0) {
                            $found = true;
                            break;
                        }
                    }
                }

                if ($found) {
                    continue;
                }

                yield $value;
            }
        };

        return $clone;
    }

    /**
     * @param callable|null $comparator function(mixed $valueA, mixed $valueB): int{-1, 0, 1}
     * @return IterableValue
     */
    public function intersect(ArrayValue $other, ?callable $comparator = null)
    {
        $clone = clone $this;
        $clone->iterator = function () use ($other, $comparator) {
            $otherValues = $other->toArray();

            foreach (($this->iterator)() as $value) {
                if ($comparator === null) {
                    $found = \in_array($value, $otherValues, true);
                } else {
                    $found = false;
                    foreach ($otherValues as $otherValue) {
                        if ($comparator($otherValue, $value) === 0) {
                            $found = true;
                            break;
                        }
                    }
                }

                if (!$found) {
                    continue;
                }

                yield $value;
            }
        };

        return $clone;
    }

    /**
     * @param callable $transformer function(mixed $reduced, mixed $value): mixed
     * @param mixed $start
     * @return mixed
     */
    public function reduce(callable $transformer, $start)
    {
        foreach (($this->iterator)() as $value) {
            $start = $transformer($start, $value);
        }

        return $start;
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
        foreach (($this->iterator)() as $value) {
            return $value;
        }

        return null;
    }

    public function getIterator(): \Traversable
    {
        yield from ($this->iterator)();
    }

    public function use(iterable $iterable): IterableValue
    {
        $clone = clone $this;
        $clone->rootIterator->replaceIterable($iterable);

        return $clone;
    }
}
