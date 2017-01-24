<?php

namespace GW\Value;

final class StringsArray implements StringsValue
{
    /** @var ArrayValue */
    private $strings;

    public function __construct(ArrayValue $strings)
    {
        $this->strings = $this->mapStringValues($strings);
    }

    public static function fromArray(array $strings): self
    {
        return new self(Arrays::create($strings));
    }

    public function join(ArrayValue $other): StringsArray
    {
        return new self($this->strings->join($this->mapStringValues($other)));
    }

    public function slice(int $offset, int $length): StringsArray
    {
        return new self($this->strings->slice($offset, $length));
    }

    public function diff(ArrayValue $other, ?callable $comparator = null): StringsArray
    {
        return new self($this->strings->diff($this->mapStringValues($other), $comparator));
    }

    public function intersect(ArrayValue $other, ?callable $comparator = null): StringsArray
    {
        return new self($this->strings->intersect($this->mapStringValues($other), $comparator));
    }

    public function reduce(callable $transformer, $start)
    {
        return $this->strings->reduce($transformer, $start);
    }

    public function map(callable $transformer): StringsArray
    {
        return new self($this->strings->map($transformer));
    }

    public function filter(callable $transformer): StringsArray
    {
        return new self($this->strings->filter($transformer));
    }

    public function implode(string $glue): StringValue
    {
        return $this->strings->implode($glue);
    }

    public function first(): ?StringValue
    {
        return $this->strings->first();
    }

    public function last(): ?StringValue
    {
        return $this->strings->last();
    }

    public function each(callable $callback): StringsArray
    {
        $this->strings->each($callback);

        return $this;
    }

    /**
     * @param callable|null $comparator function(mixed $valueA, mixed $valueB): int{-1, 0, 1}
     * @return Collection
     */
    public function unique(?callable $comparator = null): StringsArray
    {
        return new self($this->strings->unique($comparator));
    }

    /**
     * @return StringValue[]
     */
    public function toArray(): array
    {
        return $this->strings->toArray();
    }

    public function filterEmpty(): StringsArray
    {
        return new self($this->strings->filterEmpty());
    }

    public function sort(callable $comparator): StringsArray
    {
        return new self($this->strings->sort($comparator));
    }

    public function shuffle(): StringsArray
    {
        return new self($this->strings->shuffle());
    }

    public function reverse(): StringsArray
    {
        return new self($this->strings->reverse());
    }

    public function getIterator(): \Iterator
    {
        return $this->strings->getIterator();
    }

    public function offsetExists($offset): bool
    {
        return $this->strings->offsetExists($offset);
    }

    public function offsetGet($offset): StringValue
    {
        return $this->strings->offsetGet($offset);
    }

    public function offsetSet($offset, $value)
    {
        $this->strings->offsetSet($offset, $value);
    }

    public function offsetUnset($offset)
    {
        $this->strings->offsetUnset($offset);
    }

    public function count(): int
    {
        return $this->strings->count();
    }

    public function unshift($value): StringsArray
    {
        return new self($this->strings->unshift($value));
    }

    public function shift(&$value = null): StringsArray
    {
        return new self($this->strings->shift($value));
    }

    public function push($value): StringsArray
    {
        return new self($this->strings->push($value));
    }

    public function pop(&$value = null): StringsArray
    {
        return new self($this->strings->pop($value));
    }

    public function stripTags(): StringsArray
    {
        return $this->withMapByMethod(__FUNCTION__);
    }

    public function trim(string $characterMask = self::TRIM_MASK): StringsArray
    {
        return $this->withMapByMethod(__FUNCTION__, $characterMask);
    }

    public function trimRight(string $characterMask = self::TRIM_MASK): StringsArray
    {
        return $this->withMapByMethod(__FUNCTION__, $characterMask);
    }

    public function trimLeft(string $characterMask = self::TRIM_MASK): StringsArray
    {
        return $this->withMapByMethod(__FUNCTION__, $characterMask);
    }

    public function lower(): StringsArray
    {
        return $this->withMapByMethod(__FUNCTION__);
    }

    public function upper(): StringsArray
    {
        return $this->withMapByMethod(__FUNCTION__);
    }

    public function lowerFirst(): StringsArray
    {
        return $this->withMapByMethod(__FUNCTION__);
    }

    public function upperFirst(): StringsArray
    {
        return $this->withMapByMethod(__FUNCTION__);
    }

    public function upperWords(): StringsArray
    {
        return $this->withMapByMethod(__FUNCTION__);
    }

    public function padRight(int $length, string $string = ' '): StringsArray
    {
        return $this->withMapByMethod(__FUNCTION__, $length, $string);
    }

    public function padLeft(int $length, string $string = ' '): StringsArray
    {
        return $this->withMapByMethod(__FUNCTION__, $length, $string);
    }

    public function padBoth(int $length, string $string = ' '): StringsArray
    {
        return $this->withMapByMethod(__FUNCTION__, $length, $string);
    }

    public function replace(string $search, string $replace): StringsArray
    {
        return $this->withMapByMethod(__FUNCTION__, $search, $replace);
    }

    public function replacePattern(string $pattern, string $replacement): StringsArray
    {
        return $this->withMapByMethod(__FUNCTION__, $pattern, $replacement);
    }

    public function replacePatternCallback(string $pattern, callable $callback): StringsArray
    {
        return $this->withMapByMethod(__FUNCTION__, $pattern, $callback);
    }

    public function truncate(int $length, string $postfix = '...'): StringsValue
    {
        return $this->withMapByMethod(__FUNCTION__, $length, $postfix);
    }

    public function substring(int $start, ?int $length = null): StringValue
    {
        return $this->toStringValue()->substring($start, $length);
    }

    public function postfix(StringValue $other): StringsArray
    {
        return $this->withMapByMethod(__FUNCTION__, $other);
    }

    public function length(): int
    {
        return $this->toStringValue()->length();
    }

    public function position(string $needle): ?int
    {
        return $this->toStringValue()->position($needle);
    }

    public function positionLast(string $needle): ?int
    {
        return $this->toStringValue()->positionLast($needle);
    }

    public function matchAllPatterns(string $pattern): ArrayValue
    {
        return $this->toStringValue()->matchAllPatterns($pattern);
    }

    public function matchPatterns(string $pattern): ArrayValue
    {
        return $this->toStringValue()->matchPatterns($pattern);
    }

    public function isMatching(string $pattern): bool
    {
        return $this->toStringValue()->isMatching($pattern);
    }

    public function splitByPattern(string $pattern): ArrayValue
    {
        return $this->toStringValue()->splitByPattern($pattern);
    }

    public function explode(string $delimiter): StringsValue
    {
        return $this->toStringValue()->explode($delimiter);
    }

    public function contains(string $substring): bool
    {
        return $this->toStringValue()->contains($substring);
    }

    public function toString(): string
    {
        return $this->toStringValue()->toString();
    }

    public function __toString(): string
    {
        return $this->toString();
    }

    public function notEmpty(): StringsArray
    {
        return $this->filter(Filters::notEmpty());
    }

    private function mapStringValues(ArrayValue $strings): ArrayValue
    {
        return $strings
            ->map(function ($string) {
                return is_string($string) ? Strings::create($string) : $string;
            })
            ->each(function ($string): void {
                if (!$string instanceof StringValue) {
                    throw new \InvalidArgumentException('StringsValue can contain only StringValue');
                }
            }
        );
    }

    private function withMapByMethod(string $method, ...$args): StringsArray
    {
        return new self($this->strings->map(Mappers::callMethod($method, ...$args)));
    }

    private function toStringValue(): StringValue
    {
        return $this->implode(' ');
    }

    public function isEmpty(): bool
    {
        return $this->strings->isEmpty() || $this->toStringValue()->isEmpty();
    }
}
