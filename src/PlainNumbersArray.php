<?php

namespace GW\Value;

final class PlainNumbersArray implements NumbersArray
{
    /** @var ArrayValue */
    private $numbers;

    public function __construct(ArrayValue $numbers)
    {
        $this->numbers = $this->mapArrayValueNumbers($numbers);
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
        return new self($this->numbers->unique($comparator));
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
        return new self($this->numbers->filter($filter));
    }

    public function filterEmpty(): PlainNumbersArray
    {
        return new self($this->numbers->filterEmpty());
    }

    /**
     * @param callable $transformer function(NumberValue $value): NumberValue
     */
    public function map(callable $transformer): PlainNumbersArray
    {
        return new self($this->numbers->map($transformer));
    }

    /**
     * @param callable $transformer function(NumberValue $value): iterable|NumberValue[]
     */
    public function flatMap(callable $transformer): PlainNumbersArray
    {
        return new self($this->numbers->flatMap($transformer));
    }

    /**
     * @param callable $comparator function(NumberValue $valueA, NumberValue $valueB): int{-1, 0, 1}
     */
    public function sort(callable $comparator): PlainNumbersArray
    {
        return new self($this->numbers->sort($comparator));
    }

    public function shuffle(): PlainNumbersArray
    {
        return new self($this->numbers->shuffle());
    }

    public function reverse(): PlainNumbersArray
    {
        return new self($this->numbers->reverse());
    }

    /**
     * @param NumberValue|number $value
     */
    public function unshift($value): PlainNumbersArray
    {
        return new self($this->numbers->unshift($value));
    }

    /**
     * @param NumberValue $value
     */
    public function shift(&$value = null): PlainNumbersArray
    {
        return new self($this->numbers->shift($value));
    }

    /**
     * @param NumberValue|number $value
     */
    public function push($value): PlainNumbersArray
    {
        return new self($this->numbers->push($value));
    }

    /**
     * @param NumberValue $value
     */
    public function pop(&$value = null): PlainNumbersArray
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
        return new self($this->numbers->join($other));
    }

    public function slice(int $offset, int $length): PlainNumbersArray
    {
        return new self($this->numbers->slice($offset, $length));
    }

    /**
     * @param callable|null $comparator function(NumberValue $valueA, NumberValue $valueB): int{-1, 0, 1}
     */
    public function diff(ArrayValue $other, ?callable $comparator = null): PlainNumbersArray
    {
        return new self($this->numbers->diff($this->mapArrayValueNumbers($other), $comparator));
    }

    /**
     * @param callable|null $comparator function(NumberValue $valueA, NumberValue $valueB): int{-1, 0, 1}
     */
    public function intersect(ArrayValue $other, ?callable $comparator = null): PlainNumbersArray
    {
        return new self($this->numbers->intersect($this->mapArrayValueNumbers($other), $comparator));
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
        return new self($this->numbers->notEmpty());
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

    /**
     * @param callable $reducer function(NumberValue $value): string|int|bool
     * @return AssocValue AssocValue<ArrayValue>
     */
    public function groupBy(callable $reducer): AssocValue
    {
        return $this->numbers->groupBy($reducer);
    }

    /**
     * @return ArrayValue
     */
    public function chunk(int $size): ArrayValue
    {
        return $this->numbers->chunk($size);
    }

    public function splice(int $offset, int $length, ?ArrayValue $replacement = null): PlainNumbersArray
    {
        return new self($this->numbers->splice($offset, $length, $replacement));
    }

    /**
     * @param callable $filter function(Number $value): bool
     */
    public function find(callable $filter): ?NumberValue
    {
        return $this->numbers->find($filter);
    }

    /**
     * @param callable $filter function(NumberValue $value): bool
     */
    public function findLast(callable $filter): ?NumberValue
    {
        return $this->numbers->findLast($filter);
    }

    /**
     * @param callable $filter function(NumberValue $value): bool
     */
    public function any(callable $filter): bool
    {
        return $this->numbers->any($filter);
    }

    /**
     * @param callable $filter function(NumberValue $value): bool
     */
    public function every(callable $filter): bool
    {
        return $this->numbers->every($filter);
    }

    /**
     * @param ArrayValue $numbers ArrayValue<NumberValue|int|float|string>
     * @return ArrayValue ArrayValue<NumberValue>
     */
    private function mapArrayValueNumbers(ArrayValue $numbers): ArrayValue
    {
        return $numbers->map([Wrap::class, 'number']);
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
