<?php

namespace GW\Value;

use BadMethodCallException;

interface StringsArray extends ArrayValue, StringValue
{
    // Array Value

    /**
     * @param callable $callback function(StringValue $value): void
     * @return StringsArray
     */
    public function each(callable $callback): StringsArray;

    /**
     * @param callable|null $comparator function(StringValue $valueA, StringValue $valueB): int{-1, 0, 1}
     * @return StringsArray
     */
    public function unique(?callable $comparator = null): StringsArray;

    /**
     * @return string[]
     */
    public function toArray(): array;

    /**
     * @param callable $filter function(StringValue $value): bool
     * @return StringsArray
     */
    public function filter(callable $filter): StringsArray;

    /**
     * @return StringsArray
     */
    public function filterEmpty(): StringsArray;

    /**
     * @param callable $transformer function(StringValue $value): StringValue|string
     * @return StringsArray
     */
    public function map(callable $transformer): StringsArray;

    /**
     * @param callable $transformer function(StringValue $value): iterable
     * @return StringsArray
     */
    public function flatMap(callable $transformer): StringsArray;

    /**
     * @param callable $reducer function(StringValue $value): string|int|bool
     * @return AssocValue AssocValue<StringsArray>
     */
    public function groupBy(callable $reducer): AssocValue;

    /**
     * @param callable $comparator function(StringValue $valueA, StringValue $valueB): int{-1, 0, 1}
     * @return StringsArray
     */
    public function sort(callable $comparator): StringsArray;

    /**
     * @return StringsArray
     */
    public function shuffle(): StringsArray;

    /**
     * @return StringsArray
     */
    public function reverse(): StringsArray;

    /**
     * @param StringValue|string $value
     * @return StringsArray
     */
    public function unshift($value): StringsArray;

    /**
     * @param mixed $value
     * @return StringsArray
     */
    public function shift(&$value = null): StringsArray;

    /**
     * @param StringValue|string $value
     * @return StringsArray
     */
    public function push($value): StringsArray;

    /**
     * @param mixed $value
     * @return StringsArray
     */
    public function pop(&$value = null): StringsArray;

    /**
     * @param int $offset
     */
    public function offsetExists($offset): bool;

    /**
     * @param int $offset
     */
    public function offsetGet($offset): StringValue;

    /**
     * @param int $offset
     * @param StringValue|string $value
     * @return void
     * @throws BadMethodCallException For immutable types.
     */
    public function offsetSet($offset, $value): void;

    /**
     * @param int $offset
     * @return void
     * @throws BadMethodCallException For immutable types.
     */
    public function offsetUnset($offset): void;

    /**
     * @return StringsArray
     */
    public function join(ArrayValue $other): StringsArray;

    /**
     * @return StringsArray
     */
    public function slice(int $offset, int $length): StringsArray;

    /**
     * @param ArrayValue $replacement ArrayValue<string>|ArrayValue<StringValue>
     * @return StringsArray
     */
    public function splice(int $offset, int $length, ?ArrayValue $replacement = null): StringsArray;

    /**
     * @param callable|null $comparator function(StringValue $valueA, StringValue $valueB): int{-1, 0, 1}
     * @return StringsArray
     */
    public function diff(ArrayValue $other, ?callable $comparator = null): StringsArray;

    /**
     * @param callable|null $comparator function(StringValue $valueA, StringValue $valueB): int{-1, 0, 1}
     * @return StringsArray
     */
    public function intersect(ArrayValue $other, ?callable $comparator = null): StringsArray;

    /**
     * @param callable $transformer function(mixed $reduced, StringValue $value): mixed
     * @param mixed $start
     * @return mixed
     */
    public function reduce(callable $transformer, $start);

    public function implode(string $glue): StringValue;

    /**
     * @return StringsArray
     */
    public function notEmpty(): StringsArray;

    /**
     * @return StringValue|null
     */
    public function first(): ?StringValue;

    /**
     * @return StringValue|null
     */
    public function last(): ?StringValue;

    public function find(callable $filter): ?StringValue;

    public function findLast(callable $filter): ?StringValue;

    // StringValue

    /**
     * @param callable $transformer function(string $value): string
     * @return StringsArray
     */
    public function transform(callable $transformer): StringsArray;

    /**
     * @return StringsArray
     */
    public function stripTags(): StringsArray;

    /**
     * @return StringsArray
     * @param string|StringValue $characterMask
     */
    public function trim($characterMask = self::TRIM_MASK): StringsArray;

    /**
     * @return StringsArray
     * @param string|StringValue $characterMask
     */
    public function trimRight($characterMask = self::TRIM_MASK): StringsArray;

    /**
     * @return StringsArray
     * @param string|StringValue $characterMask
     */
    public function trimLeft($characterMask = self::TRIM_MASK): StringsArray;

    /**
     * @return StringsArray
     */
    public function lower(): StringsArray;

    /**
     * @return StringsArray
     */
    public function upper(): StringsArray;

    /**
     * @return StringsArray
     */
    public function lowerFirst(): StringsArray;

    /**
     * @return StringsArray
     */
    public function upperFirst(): StringsArray;

    /**
     * @return StringsArray
     */
    public function upperWords(): StringsArray;

    /**
     * @return StringsArray
     * @param string|StringValue $string
     */
    public function padRight(int $length, $string = ' '): StringsArray;

    /**
     * @return StringsArray
     * @param string|StringValue $string
     */
    public function padLeft(int $length, $string = ' '): StringsArray;

    /**
     * @return StringsArray
     * @param string|StringValue $string
     */
    public function padBoth(int $length, $string = ' '): StringsArray;

    /**
     * @return StringsArray
     * @param string|StringValue $search
     * @param string|StringValue $replace
     */
    public function replace($search, $replace): StringsArray;

    /**
     * @return StringsArray
     * @param string|StringValue $pattern
     * @param string|StringValue $replacement
     */
    public function replacePattern($pattern, $replacement): StringsArray;

    /**
     * @return StringsArray
     * @param string|StringValue $pattern
     */
    public function replacePatternCallback($pattern, callable $callback): StringsArray;

    /**
     * @return StringsArray
     * @param string|StringValue $postfix
     */
    public function truncate(int $length, $postfix = '...'): StringsArray;

    /**
     * @return StringsArray
     */
    public function substring(int $start, ?int $length = null): StringsArray;

    /**
     * @return StringsArray
     * @param string|StringValue $other
     */
    public function postfix($other): StringsArray;

    /**
     * @return StringsArray
     * @param string|StringValue $other
     */
    public function prefix($other): StringsArray;

    /**
     * @return ArrayValue ArrayValue<StringValue>
     */
    public function toArrayValue(): ArrayValue;

    /**
     * @return AssocValue AssocValue<string, StringValue>
     */
    public function toAssocValue(): AssocValue;
}
