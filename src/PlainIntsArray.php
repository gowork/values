<?php declare(strict_types=1);

namespace GW\Value;

use Traversable;

final class PlainIntsArray implements IntsArray
{
    /** @var ArrayValue|IntValue[] */
    private $ints;

    public function __construct(ArrayValue $ints)
    {
        $this->ints = $this->mapIntsValues($ints);
    }

    public function join(ArrayValue $other): PlainIntsArray
    {
        return new self($this->ints->join($this->mapIntsValues($other)));
    }

    public function slice(int $offset, int $length): PlainIntsArray
    {
        return new self($this->ints->slice($offset, $length));
    }

    /**
     * @param ArrayValue $replacement ArrayValue<int>|ArrayValue<IntsArray>
     */
    public function splice(int $offset, int $length, ?ArrayValue $replacement = null): PlainIntsArray
    {
        return new self($this->ints->splice($offset, $length, $replacement));
    }

    public function diff(ArrayValue $other, ?callable $comparator = null): PlainIntsArray
    {
        return new self($this->ints->diff($this->mapIntsValues($other), $comparator));
    }

    public function intersect(ArrayValue $other, ?callable $comparator = null): PlainIntsArray
    {
        return new self($this->ints->intersect($this->mapIntsValues($other), $comparator));
    }

    public function reduce(callable $transformer, $start)
    {
        return $this->ints->reduce($transformer, $start);
    }

    public function map(callable $transformer): PlainIntsArray
    {
        return new self($this->ints->map($transformer));
    }

    public function flatMap(callable $transformer): PlainIntsArray
    {
        return new self($this->ints->flatMap($transformer));
    }

    public function groupBy(callable $reducer): AssocValue
    {
        return $this->ints
            ->groupBy($reducer)
            ->map(function (ArrayValue $value): IntsArray {
                return $value->toIntsArray();
            });
    }

    /**
     * @param int $size
     * @return ArrayValue ArrayValue<array<IntsArray>>
     */
    public function chunk(int $size): ArrayValue
    {
        return $this->ints->chunk($size);
    }

    public function filter(callable $filter): PlainIntsArray
    {
        return new self($this->ints->filter($filter));
    }

    public function implode(string $glue): StringValue
    {
        return $this->ints->toStringsArray()->implode($glue);
    }

    public function first(): ?IntValue
    {
        return $this->ints->first();
    }

    public function last(): ?IntValue
    {
        return $this->ints->last();
    }

    public function find(callable $filter): ?IntValue
    {
        return $this->ints->find($filter);
    }

    public function findLast(callable $filter): ?IntValue
    {
        return $this->ints->findLast($filter);
    }

    public function hasElement($element): bool
    {
        $intValue = $element instanceof IntValue ? $element : Wrap::int($element);

        return \in_array($intValue, $this->ints->toArray(), false);
    }

    public function any(callable $filter): bool
    {
        return $this->ints->any($filter);
    }

    public function every(callable $filter): bool
    {
        return $this->ints->every($filter);
    }

    public function each(callable $callback): PlainIntsArray
    {
        $this->ints->each($callback);

        return $this;
    }

    /**
     * @param callable|null $comparator function(mixed $valueA, mixed $valueB): int{-1, 0, 1}
     */
    public function unique(?callable $comparator = null): PlainIntsArray
    {
        return new self($this->ints->unique($comparator));
    }

    /**
     * @return int[]
     */
    public function toArray(): array
    {
        return $this->ints
            ->map(function(IntsArray $item): int {
                return $item->toInt();
            })
            ->toArray();
    }

    public function filterEmpty(): PlainIntsArray
    {
        return new self($this->ints->filterEmpty());
    }

    public function sort(callable $comparator): PlainIntsArray
    {
        return new self($this->ints->sort($comparator));
    }

    public function shuffle(): PlainIntsArray
    {
        return new self($this->ints->shuffle());
    }

    public function reverse(): PlainIntsArray
    {
        return new self($this->ints->reverse());
    }

    public function getIterator(): \Traversable
    {
        return $this->ints->getIterator();
    }

    public function offsetExists($offset): bool
    {
        return $this->ints->offsetExists($offset);
    }

    public function offsetGet($offset): IntsArray
    {
        return $this->ints->offsetGet($offset);
    }

    public function offsetSet($offset, $value): void
    {
        $this->ints->offsetSet($offset, $value);
    }

    public function offsetUnset($offset): void
    {
        $this->ints->offsetUnset($offset);
    }

    public function count(): int
    {
        return $this->ints->count();
    }

    public function unshift($value): PlainIntsArray
    {
        return new self($this->ints->unshift($value));
    }

    public function shift(&$value = null): PlainIntsArray
    {
        return new self($this->ints->shift($value));
    }

    public function push($value): PlainIntsArray
    {
        return new self($this->ints->push($value));
    }

    public function pop(&$value = null): PlainIntsArray
    {
        return new self($this->ints->pop($value));
    }

    private function mapIntsValues(ArrayValue $ints): ArrayValue
    {
        return $ints
            ->map(
                function ($int) {
                    return is_scalar($int) ? Wrap::int((int)$int) : $int;
                }
            )
            ->each(
                function ($int): void {
                    if (!$int instanceof IntValue) {
                        throw new \InvalidArgumentException('IntsArray can contain only IntValue');
                    }
                }
            );
    }

    public function toStringsArray(): StringsArray
    {
        return $this->ints->toStringsArray();
    }

    public function toIntsArray(): IntsArray
    {
        return $this;
    }

    /**
     * @return IntValue
     * @param int|IntValue $number
     */
    public function add($number)
    {
        $clone = clone $this;
        $clone->ints = $this->ints->map(function (IntValue $intValue) use ($number): IntValue {
            return $intValue->add($number);
        });

        return $clone;
    }

    /**
     * @return IntValue
     * @param int|IntValue $number
     */
    public function substract($number)
    {
        $clone = clone $this;
        $clone->ints = $this->ints->map(function (IntValue $intValue) use ($number): IntValue {
            return $intValue->substract($number);
        });

        return $clone;
    }

    /**
     * @return IntValue
     * @param int|IntValue $number
     */
    public function multiply($number)
    {
        $clone = clone $this;
        $clone->ints = $this->ints->map(function (IntValue $intValue) use ($number): IntValue {
            return $intValue->multiply($number);
        });

        return $clone;
    }

    public function toString(): string
    {
        return $this->toStringsArray()->implode(', ')->toString();
    }

    public function sum(): IntValue
    {
        return $this->ints->reduce(function (?IntValue $reduced, IntValue $intValue): IntValue {
            if ($reduced === null) {
                return $intValue;
            }

            return $reduced->add($intValue);
        }, null);
    }

    public function toInt(): int
    {
        return $this->sum()->toInt();
    }

    /**
     * @return IntsArray
     */
    public function notEmpty()
    {
        return $this;
    }

    /**
     * @return ArrayValue ArrayValue<IntValue>
     */
    public function toArrayValue(): ArrayValue
    {
        return $this->ints;
    }

    /**
     * @return AssocValue AssocValue<string, IntValue>
     */
    public function toAssocValue(): AssocValue
    {
        return $this->ints->toAssocValue();
    }

    public function isEmpty(): bool
    {
        return $this->ints->isEmpty();
    }

    /**
     * @param int|IntValue $number
     */
    private function num($number): int
    {
        return $number instanceof IntValue ? $number->toInt() : $number;
    }
}
