<?php

namespace GW\Value;

final class PlainStringsArray implements StringsArray
{
    /** @var ArrayValue */
    private $strings;

    public function __construct(ArrayValue $strings)
    {
        $this->strings = $this->mapStringValues($strings);
    }

    public static function fromArray(array $strings): self
    {
        return new self(Wrap::array($strings));
    }

    public function join(ArrayValue $other): PlainStringsArray
    {
        return new self($this->strings->join($this->mapStringValues($other)));
    }

    public function slice(int $offset, int $length): PlainStringsArray
    {
        return new self($this->strings->slice($offset, $length));
    }

    public function diff(ArrayValue $other, ?callable $comparator = null): PlainStringsArray
    {
        return new self($this->strings->diff($this->mapStringValues($other), $comparator));
    }

    public function intersect(ArrayValue $other, ?callable $comparator = null): PlainStringsArray
    {
        return new self($this->strings->intersect($this->mapStringValues($other), $comparator));
    }

    public function reduce(callable $transformer, $start)
    {
        return $this->strings->reduce($transformer, $start);
    }

    public function map(callable $transformer): PlainStringsArray
    {
        return new self($this->strings->map($transformer));
    }

    public function filter(callable $filter): PlainStringsArray
    {
        return new self($this->strings->filter($filter));
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

    public function hasElement($element): bool
    {
        $stringValue = $element instanceof StringValue ? $element : Wrap::string($element);

        return in_array($stringValue, $this->strings->toArray(), false);
    }

    public function each(callable $callback): PlainStringsArray
    {
        $this->strings->each($callback);

        return $this;
    }

    /**
     * @param callable|null $comparator function(mixed $valueA, mixed $valueB): int{-1, 0, 1}
     * @return Collection
     */
    public function unique(?callable $comparator = null): PlainStringsArray
    {
        return new self($this->strings->unique($comparator));
    }

    /**
     * @return StringValue[]
     */
    public function toArray(): array
    {
        return $this->strings
            ->map(function(StringValue $item): string {
                return $item->toString();
            })
            ->toArray();
    }

    public function filterEmpty(): PlainStringsArray
    {
        return new self($this->strings->filterEmpty());
    }

    public function sort(callable $comparator): PlainStringsArray
    {
        return new self($this->strings->sort($comparator));
    }

    public function shuffle(): PlainStringsArray
    {
        return new self($this->strings->shuffle());
    }

    public function reverse(): PlainStringsArray
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

    public function unshift($value): PlainStringsArray
    {
        return new self($this->strings->unshift($value));
    }

    public function shift(&$value = null): PlainStringsArray
    {
        return new self($this->strings->shift($value));
    }

    public function push($value): PlainStringsArray
    {
        return new self($this->strings->push($value));
    }

    public function pop(&$value = null): PlainStringsArray
    {
        return new self($this->strings->pop($value));
    }

    public function transform(callable $transformer): PlainStringsArray
    {
        return $this->withMapByMethod(__FUNCTION__, $transformer);
    }

    public function stripTags(): PlainStringsArray
    {
        return $this->withMapByMethod(__FUNCTION__);
    }

    public function trim(string $characterMask = self::TRIM_MASK): PlainStringsArray
    {
        return $this->withMapByMethod(__FUNCTION__, $characterMask);
    }

    public function trimRight(string $characterMask = self::TRIM_MASK): PlainStringsArray
    {
        return $this->withMapByMethod(__FUNCTION__, $characterMask);
    }

    public function trimLeft(string $characterMask = self::TRIM_MASK): PlainStringsArray
    {
        return $this->withMapByMethod(__FUNCTION__, $characterMask);
    }

    public function lower(): PlainStringsArray
    {
        return $this->withMapByMethod(__FUNCTION__);
    }

    public function upper(): PlainStringsArray
    {
        return $this->withMapByMethod(__FUNCTION__);
    }

    public function lowerFirst(): PlainStringsArray
    {
        return $this->withMapByMethod(__FUNCTION__);
    }

    public function upperFirst(): PlainStringsArray
    {
        return $this->withMapByMethod(__FUNCTION__);
    }

    public function upperWords(): PlainStringsArray
    {
        return $this->withMapByMethod(__FUNCTION__);
    }

    public function padRight(int $length, string $string = ' '): PlainStringsArray
    {
        return $this->withMapByMethod(__FUNCTION__, $length, $string);
    }

    public function padLeft(int $length, string $string = ' '): PlainStringsArray
    {
        return $this->withMapByMethod(__FUNCTION__, $length, $string);
    }

    public function padBoth(int $length, string $string = ' '): PlainStringsArray
    {
        return $this->withMapByMethod(__FUNCTION__, $length, $string);
    }

    public function replace(string $search, string $replace): PlainStringsArray
    {
        return $this->withMapByMethod(__FUNCTION__, $search, $replace);
    }

    public function replacePattern(string $pattern, string $replacement): PlainStringsArray
    {
        return $this->withMapByMethod(__FUNCTION__, $pattern, $replacement);
    }

    public function replacePatternCallback(string $pattern, callable $callback): PlainStringsArray
    {
        return $this->withMapByMethod(__FUNCTION__, $pattern, $callback);
    }

    public function truncate(int $length, string $postfix = '...'): StringsArray
    {
        return $this->withMapByMethod(__FUNCTION__, $length, $postfix);
    }

    public function substring(int $start, ?int $length = null): StringValue
    {
        return $this->toStringValue()->substring($start, $length);
    }

    public function postfix(StringValue $other): PlainStringsArray
    {
        return $this->withMapByMethod(__FUNCTION__, $other);
    }

    public function prefix(StringValue $other): PlainStringsArray
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

    public function explode(string $delimiter): StringsArray
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

    public function notEmpty(): PlainStringsArray
    {
        return $this->filter(Filters::notEmpty());
    }

    private function mapStringValues(ArrayValue $strings): ArrayValue
    {
        return $strings
            ->map(function ($string) {
                return is_scalar($string) ? Wrap::string((string)$string) : $string;
            })
            ->each(function ($string): void {
                if (!$string instanceof StringValue) {
                    throw new \InvalidArgumentException('StringsValue can contain only StringValue');
                }
            }
        );
    }

    private function withMapByMethod(string $method, ...$args): PlainStringsArray
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

    /**
     * @return ArrayValue<StringValue>
     */
    public function toArrayValue(): ArrayValue
    {
        return $this->strings;
    }

    /**
     * @return AssocValue<string, StringValue>
     */
    public function toAssocValue(): AssocValue
    {
        return $this->strings->toAssocValue();
    }

    public function toStringsArray(): StringsArray
    {
        return $this;
    }
}
