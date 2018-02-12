<?php declare(strict_types=1);

namespace GW\Value;

final class IterableValue implements ArrayValue
{
    /** @var iterable */
    private $iterator;

    public function __construct(iterable $iterator)
    {
        $this->iterator = $iterator;
    }

    /**
     * @param callable $callback function(mixed $value): void
     * @return ArrayValue
     */
    public function each(callable $callback)
    {
        foreach ($this->iterator as $value) {
            $callback($value);
        }

        return $this;
    }

    /**
     * @param callable|null $comparator function(mixed $valueA, mixed $valueB): int{-1, 0, 1}
     * @return ArrayValue
     */
    public function unique(?callable $comparator = null)
    {
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

        foreach ($this->iterator as $value) {
            $return[] = $value;
        }

        return $return;
    }

    /**
     * @param callable $filter function(mixed $value): bool { ... }
     */
    public function filter(callable $filter): IterableValue
    {
        $currentIterator = $this->iterator;

        $clone = clone $this;
        $clone->iterator = function () use ($filter, $currentIterator) {
            foreach ($currentIterator as $value) {
                if ($filter($value)) {
                    yield $value;
                }
            }
        };

        return $clone;
    }

    public function filterEmpty(): IterableValue
    {
        return $this->filter(Filters::notEmpty());
    }

    /**
     * @param callable $transformer function(mixed $value): mixed { ... }
     * @return ArrayValue
     */
    public function map(callable $transformer)
    {
        $currentIterator = $this->iterator;

        $clone = clone $this;
        $clone->iterator = function () use ($transformer, $currentIterator) {
            foreach ($currentIterator as $value) {
                yield $transformer($value);
            }
        };

        return $clone;
    }

    /**
     * @param callable $transformer function(mixed $value): iterable { ... }
     * @return ArrayValue
     */
    public function flatMap(callable $transformer)
    {
        $currentIterator = $this->iterator;

        $clone = clone $this;
        $clone->iterator = function () use ($transformer, $currentIterator) {
            foreach ($currentIterator as $value) {
                yield from $transformer($value);
            }
        };

        return $clone;
    }

    /**
     * @param callable $comparator function(mixed $valueA, mixed $valueB): int{-1, 0, 1}
     * @return ArrayValue
     */
    public function sort(callable $comparator)
    {
        return Wrap::array($this->toArray())->sort($comparator);
    }

    /**
     * @return ArrayValue
     */
    public function shuffle()
    {
        return Wrap::array($this->toArray())->shuffle();
    }

    /**
     * @return ArrayValue
     */
    public function reverse()
    {
        return Wrap::array($this->toArray())->reverse();
    }

    /**
     * @param mixed $value
     * @return ArrayValue
     */
    public function unshift($value)
    {
        $currentIterator = $this->iterator;

        $clone = clone $this;
        $clone->iterator = function () use ($value, $currentIterator) {
            yield $value;
            yield from $currentIterator;
        };

        return $clone;
    }

    /**
     * @param mixed $value
     * @return ArrayValue
     */
    public function shift(&$value = null)
    {
        return Wrap::array($this->toArray())->shift($value);
    }

    /**
     * @param mixed $value
     * @return ArrayValue
     */
    public function push($value)
    {
        $currentIterator = $this->iterator;

        $clone = clone $this;
        $clone->iterator = function () use ($value, $currentIterator) {
            yield from $currentIterator;
            yield $value;
        };

        return $clone;
    }

    /**
     * @param mixed $value
     * @return ArrayValue
     */
    public function pop(&$value = null)
    {
        return Wrap::array($this->toArray())->pop($value);
    }

    /**
     * @param int $offset
     */
    public function offsetExists($offset): bool
    {
        return Wrap::array($this->toArray())->offsetExists($offset);
    }

    /**
     * @param int $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return Wrap::array($this->toArray())->offsetGet($offset);
    }

    /**
     * @param int $offset
     * @param mixed $value
     * @return void
     * @throws \BadMethodCallException For immutable types.
     */
    public function offsetSet($offset, $value)
    {
        throw new \BadMethodCallException('ArrayValue is immutable');
    }

    /**
     * @param int $offset
     * @return void
     * @throws \BadMethodCallException For immutable types.
     */
    public function offsetUnset($offset)
    {
        throw new \BadMethodCallException('ArrayValue is immutable');
    }

    /**
     * @return ArrayValue
     */
    public function join(ArrayValue $other)
    {
        $currentIterator = $this->iterator;

        $clone = clone $this;
        $clone->iterator = function () use ($other, $currentIterator) {
            yield from $currentIterator;
            yield $other;
        };

        return $clone;
    }

    /**
     * @return ArrayValue
     */
    public function slice(int $offset, int $length)
    {
        $currentIterator = $this->iterator;

        $clone = clone $this;
        $clone->iterator = function () use ($offset, $length, $currentIterator) {
            foreach ($currentIterator as $value) {
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
     * @return ArrayValue
     */
    public function diff(ArrayValue $other, ?callable $comparator = null)
    {
        $currentIterator = $this->iterator;

        $clone = clone $this;
        $clone->iterator = function () use ($other, $comparator, $currentIterator) {
            $otherValues = $other->toArray();

            foreach ($currentIterator as $value) {
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
     * @return ArrayValue
     */
    public function intersect(ArrayValue $other, ?callable $comparator = null)
    {
        $currentIterator = $this->iterator;

        $clone = clone $this;
        $clone->iterator = function () use ($other, $comparator, $currentIterator) {
            $otherValues = $other->toArray();

            foreach ($currentIterator as $value) {
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
        foreach ($this->iterator as $value) {
            $start = $transformer($value);
        }

        return $start;
    }

    public function implode(string $glue): StringValue
    {
        return Wrap::array($this->toArray())->implode($glue);
    }

    /**
     * @return ArrayValue
     */
    public function notEmpty()
    {
        return $this->filter(Filters::notEmpty());
    }

    public function toAssocValue(): AssocValue
    {
        return Wrap::assocArray($this->toArray());
    }

    public function toStringsArray(): StringsArray
    {
        return Wrap::stringsArray($this->toArray());
    }

    /**
     * @return mixed
     */
    public function first()
    {
        foreach ($this->iterator as $value) {
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

        foreach ($this->iterator as $value) {
            // iterate only
        }

        return $value;
    }

    /**
     * @param mixed $element
     */
    public function hasElement($element): bool
    {
        foreach ($this->iterator as $value) {
            if ($value === $element) {
                return true;
            }
        }

        return false;
    }

    public function getIterator(): \Traversable
    {
        foreach ($this->iterator as $value) {
            yield $value;
        }
    }

    public function count(): int
    {
        $counter = 0;

        foreach ($this->iterator as $value) {
            $counter++;
        }

        return $counter;
    }

    public function isEmpty(): bool
    {
        foreach ($this->iterator as $value) {
            return false;
        }

        return true;
    }
}
