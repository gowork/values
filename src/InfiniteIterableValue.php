<?php declare(strict_types=1);

namespace GW\Value;

final class InfiniteIterableValue implements IterableValue
{
    /** @var \Closure */
    private $rootIterator;

    /** @var object&callable */
    private $iterator;

    /** @var array|null */
    private $values;

    public function __construct(iterable $iterable, bool $rewindable = false)
    {
        $this->iterator = new class ($iterable, $rewindable) {
            /** @var iterable */
            private $iterable;
            /** @var bool */
            private $used = false;
            /** @var array|null */
            private $values;
            /** @var bool */
            private $rewindable;

            public function __construct(iterable $iterable, bool $rewindable)
            {
                $this->iterable = $iterable;
                $this->rewindable = $rewindable;
            }

            public function replaceIterable($iterable): void
            {
                $this->iterable = $iterable;
                $this->used = false;
            }

            public function __invoke(): iterable
            {
                if ($this->rewindable) {
                    yield from $this->rewindableIterator();
                    return;
                }

                if ($this->used) {
                    throw new \RuntimeException('IterableValue is already used.');
                }

                yield from $this->iterable;
                $this->used = true;
            }

            private function rewindableIterator(): iterable
            {
                if ($this->values !== null) {
                    yield from $this->values;
                    return;
                }

                $this->values = [];

                foreach ($this->iterable as $item) {
                    yield $item;
                    $this->values[] = $item;
                }
            }
        };

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
        $currentIterator = $this->iterator;

        $clone = clone $this;
        $clone->iterator = function () use ($filter, $currentIterator) {
            foreach ($currentIterator() as $value) {
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
        $currentIterator = $this->iterator;

        $clone = clone $this;
        $clone->iterator = function () use ($transformer, $currentIterator) {
            foreach ($currentIterator() as $value) {
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
        $currentIterator = $this->iterator;

        $clone = clone $this;
        $clone->iterator = function () use ($transformer, $currentIterator) {
            foreach ($currentIterator() as $value) {
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
        $currentIterator = $this->iterator;

        $clone = clone $this;
        $clone->iterator = function () use ($value, $currentIterator) {
            yield $value;
            yield from $currentIterator();
        };

        return $clone;
    }

    /**
     * @param mixed $value
     * @return IterableValue
     */
    public function push($value)
    {
        $currentIterator = $this->iterator;

        $clone = clone $this;
        $clone->iterator = function () use ($value, $currentIterator) {
            yield from $currentIterator();
            yield $value;
        };

        return $clone;
    }

    /**
     * @return IterableValue
     */
    public function join(IterableValue $other)
    {
        $currentIterator = $this->iterator;

        $clone = clone $this;
        $clone->iterator = function () use ($other, $currentIterator) {
            yield from $currentIterator();
            yield from $other;
        };

        return $clone;
    }

    /**
     * @return IterableValue
     */
    public function slice(int $offset, int $length)
    {
        $currentIterator = $this->iterator;

        $clone = clone $this;
        $clone->iterator = function () use ($offset, $length, $currentIterator) {
            foreach ($currentIterator() as $value) {
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
        $currentIterator = $this->iterator;

        $clone = clone $this;
        $clone->iterator = function () use ($other, $comparator, $currentIterator) {
            $otherValues = $other->toArray();

            foreach ($currentIterator() as $value) {
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
        $currentIterator = $this->iterator;

        $clone = clone $this;
        $clone->iterator = function () use ($other, $comparator, $currentIterator) {
            $otherValues = $other->toArray();

            foreach ($currentIterator() as $value) {
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
