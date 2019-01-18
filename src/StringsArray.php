<?php

namespace GW\Value;

interface StringsArray extends ArrayValue, StringValue
{
    // Array Value

    /**
     * @param callable $callback function(StringValue $value): void
     * @return StringsArray
     */
    public function each(callable $callback);

    /**
     * @param callable|null $comparator function(StringValue $valueA, StringValue $valueB): int{-1, 0, 1}
     * @return StringsArray
     */
    public function unique(?callable $comparator = null);

    /**
     * @return string[]
     */
    public function toArray(): array;

    /**
     * @param callable $filter function(StringValue $value): bool
     * @return StringsArray
     */
    public function filter(callable $filter);

    /**
     * @return StringsArray
     */
    public function filterEmpty();

    /**
     * @param callable $transformer function(StringValue $value): StringValue|string
     * @return StringsArray
     */
    public function map(callable $transformer);

    /**
     * @param callable $transformer function(StringValue $value): iterable
     * @return StringsArray
     */
    public function flatMap(callable $transformer);

    /**
     * @param callable $reducer function(StringValue $value): string|int|bool
     * @return AssocValue AssocValue<StringsArray>
     */
    public function groupBy(callable $reducer): AssocValue;

    /**
     * @param callable $comparator function(StringValue $valueA, StringValue $valueB): int{-1, 0, 1}
     * @return StringsArray
     */
    public function sort(callable $comparator);

    /**
     * @return StringsArray
     */
    public function shuffle();

    /**
     * @return StringsArray
     */
    public function reverse();

    /**
     * @param StringValue|string $value
     * @return StringsArray
     */
    public function unshift($value);

    /**
     * @param mixed $value
     * @return StringsArray
     */
    public function shift(&$value = null);

    /**
     * @param StringValue|string $value
     * @return StringsArray
     */
    public function push($value);

    /**
     * @param mixed $value
     * @return StringsArray
     */
    public function pop(&$value = null);

    /**
     * @param int $offset
     */
    public function offsetExists($offset): bool;

    /**
     * @param int $offset
     * @return StringValue
     */
    public function offsetGet($offset);

    /**
     * @param int $offset
     * @param StringValue|string $value
     * @return void
     * @throws \BadMethodCallException For immutable types.
     */
    public function offsetSet($offset, $value);

    /**
     * @param int $offset
     * @return void
     * @throws \BadMethodCallException For immutable types.
     */
    public function offsetUnset($offset);

    /**
     * @return StringsArray
     */
    public function join(ArrayValue $other);

    /**
     * @return StringsArray
     */
    public function slice(int $offset, int $length);

    /**
     * @param ArrayValue $replacement ArrayValue<string>|ArrayValue<StringValue>
     * @return StringsArray
     */
    public function splice(int $offset, int $length, ?ArrayValue $replacement = null);

    /**
     * @param callable|null $comparator function(StringValue $valueA, StringValue $valueB): int{-1, 0, 1}
     * @return StringsArray
     */
    public function diff(ArrayValue $other, ?callable $comparator = null);

    /**
     * @param callable|null $comparator function(StringValue $valueA, StringValue $valueB): int{-1, 0, 1}
     * @return StringsArray
     */
    public function intersect(ArrayValue $other, ?callable $comparator = null);

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
    public function notEmpty();

    /**
     * @return StringValue|null
     */
    public function first();

    /**
     * @return StringValue|null
     */
    public function last();

    public function find(callable $filter): ?StringValue;

    public function findLast(callable $filter): ?StringValue;

    // StringValue

    /**
     * @param callable $transformer function(string $value): string
     * @return StringsArray
     */
    public function transform(callable $transformer);

    /**
     * @return StringsArray
     */
    public function stripTags();

    /**
     * @return StringsArray
     * @param string|StringValue $characterMask
     */
    public function trim($characterMask = self::TRIM_MASK);

    /**
     * @return StringsArray
     * @param string|StringValue $characterMask
     */
    public function trimRight($characterMask = self::TRIM_MASK);

    /**
     * @return StringsArray
     * @param string|StringValue $characterMask
     */
    public function trimLeft($characterMask = self::TRIM_MASK);

    /**
     * @return StringsArray
     */
    public function lower();

    /**
     * @return StringsArray
     */
    public function upper();

    /**
     * @return StringsArray
     */
    public function lowerFirst();

    /**
     * @return StringsArray
     */
    public function upperFirst();

    /**
     * @return StringsArray
     */
    public function upperWords();

    /**
     * @return StringsArray
     * @param string|StringValue $string
     */
    public function padRight(int $length, $string = ' ');

    /**
     * @return StringsArray
     * @param string|StringValue $string
     */
    public function padLeft(int $length, $string = ' ');

    /**
     * @return StringsArray
     * @param string|StringValue $string
     */
    public function padBoth(int $length, $string = ' ');

    /**
     * @return StringsArray
     * @param string|StringValue $search
     * @param string|StringValue $replace
     */
    public function replace($search, $replace);

    /**
     * @return StringsArray
     * @param string|StringValue $pattern
     * @param string|StringValue $replacement
     */
    public function replacePattern($pattern, $replacement);

    /**
     * @return StringsArray
     * @param string|StringValue $pattern
     */
    public function replacePatternCallback($pattern, callable $callback);

    /**
     * @return StringsArray
     * @param string|StringValue $postfix
     */
    public function truncate(int $length, $postfix = '...');

    /**
     * @return StringsArray
     */
    public function substring(int $start, ?int $length = null);

    /**
     * @return StringsArray
     * @param string|StringValue $other
     */
    public function postfix($other);

    /**
     * @return StringsArray
     * @param string|StringValue $other
     */
    public function prefix($other);

    /**
     * @return ArrayValue ArrayValue<StringValue>
     */
    public function toArrayValue(): ArrayValue;

    /**
     * @return AssocValue AssocValue<string, StringValue>
     */
    public function toAssocValue(): AssocValue;
}
