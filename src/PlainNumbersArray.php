<?php

namespace GW\Value;

final class PlainNumbersArray implements NumbersArray
{
    /** @var ArrayValue */
    private $numbers;

    public function __construct(ArrayValue $numbers)
    {
        $this->numbers = $numbers
            ->map(function($number): NumberValue {
                return Wrap::number($number);
            });
    }

    public static function fromNumbers(array $numbers): self
    {
        return new self(Wrap::array($numbers));
    }

    public function first(): ?NumberValue
    {
        return $this->numbers->first();
    }

    public function last(): ?NumberValue
    {
        return $this->numbers->last();
    }

    /**
     * @param NumberValue|number|string $element
     */
    public function hasElement($element): bool
    {
        $numberValue = $element instanceof NumberValue ? $element : Wrap::number($element);

        return \in_array($numberValue, $this->toArray(), false);
    }

    public function getIterator()
    {
        return $this->numbers->getIterator();
    }

    public function count(): int
    {
        return $this->numbers->count();
    }

    public function sum(): NumberValue
    {
        return $this->reduceBy(
            function(NumberValue $sum, NumberValue $value): NumberValue {
                return $sum->add($value);
            },
            IntegerNumber::zero()
        );
    }

    public function avg(): NumberValue
    {
        return $this->sum()->divide(Wrap::number($this->count()));
    }

    public function min(): NumberValue
    {
        return $this->reduceBy(
            function (NumberValue $min, NumberValue $next): NumberValue {
                return $next->lesserThan($min) ? $next : $min;
            }
        );
    }

    public function max(): NumberValue
    {
        return $this->reduceBy(
            function (NumberValue $max, NumberValue $next): NumberValue {
                return $next->greaterThan($max) ? $next : $max;
            }
        );
    }

    /**
     * @param callable $callback function(NumberValue $value): void
     */
    public function each(callable $callback): PlainNumbersArray
    {
        $this->numbers->each($callback);

        return $this;
    }

    /**
     * @param callable|null $comparator function(NumberValue $valueA, NumberValue $valueB): int{-1, 0, 1}
     */
    public function unique(?callable $comparator = null): PlainNumbersArray
    {
        return $this->withRearrangedNumbers(__FUNCTION__, $comparator);
    }

    /**
     * @return NumberValue[]
     */
    public function toArray(): array
    {
        return $this->numbers->toArray();
    }

    /**
     * @param callable $filter function(NumberValue $value): bool
     */
    public function filter(callable $filter): PlainNumbersArray
    {
        return $this->withRearrangedNumbers(__FUNCTION__, $filter);
    }

    public function filterEmpty(): PlainNumbersArray
    {
        return $this->withRearrangedNumbers(__FUNCTION__);
    }

    /**
     * @param callable $transformer function(NumberValue $value): NumberValue
     */
    public function map(callable $transformer): PlainNumbersArray
    {
        return $this->withTransformedNumbers(__FUNCTION__, $transformer);
    }

    /**
     * @param callable $transformer function(NumberValue $value): iterable|NumberValue[]
     */
    public function flatMap(callable $transformer): PlainNumbersArray
    {
        return $this->withTransformedNumbers(__FUNCTION__, $transformer);
    }

    /**
     * @param callable $comparator function(NumberValue $valueA, NumberValue $valueB): int{-1, 0, 1}
     */
    public function sort(callable $comparator): PlainNumbersArray
    {
        return $this->withRearrangedNumbers(__FUNCTION__, $comparator);
    }

    /**
     * @return NumbersArray
     */
    public function shuffle(): PlainNumbersArray
    {
        return $this->withRearrangedNumbers(__FUNCTION__);
    }

    public function reverse(): PlainNumbersArray
    {
        return $this->withRearrangedNumbers(__FUNCTION__);
    }

    /**
     * @param NumberValue|number $value
     * @return NumbersArray
     */
    public function unshift($value)
    {
        return new self($this->numbers->unshift($value));
    }

    /**
     * @param NumberValue $value
     * @return NumbersArray
     */
    public function shift(&$value = null)
    {
        return new self($this->numbers->shift($value));
    }

    /**
     * @param NumberValue|number $value
     * @return NumbersArray
     */
    public function push($value)
    {
        return new self($this->numbers->push($value));
    }

    /**
     * @param NumberValue $value
     * @return NumbersArray
     */
    public function pop(&$value = null)
    {
        return new self($this->numbers->pop($value));
    }

    /**
     * @param int $offset
     */
    public function offsetExists($offset): bool
    {
        return $this->numbers->offsetExists($offset);
    }

    /**
     * @param int $offset
     * @return NumberValue
     */
    public function offsetGet($offset): NumberValue
    {
        return $this->numbers->offsetGet($offset);
    }

    public function offsetSet($offset, $value)
    {
        $this->numbers->offsetSet($offset, $value);
    }

    public function offsetUnset($offset)
    {
        $this->numbers->offsetUnset($offset);
    }

    public function join(ArrayValue $other): PlainNumbersArray
    {
        return $this->withTransformedNumbers(__FUNCTION__, $other);
    }

    public function slice(int $offset, int $length): PlainNumbersArray
    {
        return $this->withRearrangedNumbers(__FUNCTION__, $offset, $length);
    }

    /**
     * @param callable|null $comparator function(NumberValue $valueA, NumberValue $valueB): int{-1, 0, 1}
     */
    public function diff(ArrayValue $other, ?callable $comparator = null): PlainNumbersArray
    {
        return $this->withRearrangedNumbers(__FUNCTION__, $other, $comparator);
    }

    /**
     * @param callable|null $comparator function(NumberValue $valueA, NumberValue $valueB): int{-1, 0, 1}
     */
    public function intersect(ArrayValue $other, ?callable $comparator = null): PlainNumbersArray
    {
        return $this->withRearrangedNumbers(__FUNCTION__, $other, $comparator);
    }

    /**
     * @param callable $transformer function(mixed $reduced, NumberValue $value): mixed
     * @param mixed $start
     * @return mixed
     */
    public function reduce(callable $transformer, $start)
    {
        return $this->numbers->reduce($transformer, $start);
    }

    public function implode(string $glue): StringValue
    {
        return $this->numbers->implode($glue);
    }

    public function notEmpty(): PlainNumbersArray
    {
        return $this->withRearrangedNumbers(__FUNCTION__);
    }

    public function toAssocValue(): AssocValue
    {
        return $this->numbers->toAssocValue();
    }

    public function toStringsArray(): StringsArray
    {
        return $this->numbers->toStringsArray();
    }

    public function isEmpty(): bool
    {
        return $this->numbers->isEmpty();
    }

    private function withRearrangedNumbers(string $method, ...$args): PlainNumbersArray
    {
        $clone = clone $this;
        $clone->numbers = $this->numbers->{$method}(...$args);

        return $clone;
    }

    private function withTransformedNumbers(string $method, ...$args): PlainNumbersArray
    {
        return new self($this->numbers->{$method}(...$args));
    }

    private function reduceBy(callable $reducer, ?NumberValue $default = null): NumberValue
    {
        if ($this->count() > 0) {
            $numbers = $this->numbers->shift($first);

            return $numbers->reduce($reducer, $first);
        }

        if ($default !== null) {
            return $default;
        }

        throw new \LogicException('Cannot calculate number from empty array');
    }
}
