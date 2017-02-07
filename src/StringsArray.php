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
     * @return StringValue[]
     */
    public function toArray(): array;

    /**
     * @param callable $transformer function(StringValue $value): bool
     * @return StringsArray
     */
    public function filter(callable $transformer);

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

    // StringValue

    /**
     * @return StringsArray
     */
    public function stripTags();

    /**
     * @return StringsArray
     */
    public function trim(string $characterMask = self::TRIM_MASK);

    /**
     * @return StringsArray
     */
    public function trimRight(string $characterMask = self::TRIM_MASK);

    /**
     * @return StringsArray
     */
    public function trimLeft(string $characterMask = self::TRIM_MASK);

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
     */
    public function padRight(int $length, string $string = ' ');

    /**
     * @return StringsArray
     */
    public function padLeft(int $length, string $string = ' ');

    /**
     * @return StringsArray
     */
    public function padBoth(int $length, string $string = ' ');

    /**
     * @return StringsArray
     */
    public function replace(string $search, string $replace);

    /**
     * @return StringsArray
     */
    public function replacePattern(string $pattern, string $replacement);

    /**
     * @return StringsArray
     */
    public function replacePatternCallback(string $pattern, callable $callback);

    /**
     * @return StringsArray
     */
    public function truncate(int $length, string $postfix = '...');

    /**
     * @return StringsArray
     */
    public function substring(int $start, ?int $length = null);

    /**
     * @return StringsArray
     */
    public function postfix(StringValue $other);

    /**
     * @return StringsArray
     */
    public function prefix(StringValue $other);
}
