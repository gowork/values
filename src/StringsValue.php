<?php

namespace GW\Value;

interface StringsValue extends ArrayValue, StringValue
{
    // Array Value

    /**
     * @param callable $callback function(StringValue $value): void
     * @return StringsValue
     */
    public function each(callable $callback);

    /**
     * @param callable|null $comparator function(StringValue $valueA, StringValue $valueB): int{-1, 0, 1}
     * @return StringsValue
     */
    public function unique(?callable $comparator = null);

    /**
     * @return StringValue[]
     */
    public function toArray(): array;

    /**
     * @param callable $transformer function(StringValue $value): bool
     * @return StringsValue
     */
    public function filter(callable $transformer);

    /**
     * @return StringsValue
     */
    public function filterEmpty();

    /**
     * @param callable $transformer function(StringValue $value): StringValue|string
     * @return StringsValue
     */
    public function map(callable $transformer);

    /**
     * @param callable $comparator function(StringValue $valueA, StringValue $valueB): int{-1, 0, 1}
     * @return StringsValue
     */
    public function sort(callable $comparator);

    /**
     * @return StringsValue
     */
    public function shuffle();

    /**
     * @return StringsValue
     */
    public function reverse();

    /**
     * @param StringValue|string $value
     * @return StringsValue
     */
    public function unshift($value);

    /**
     * @param mixed $value
     * @return StringsValue
     */
    public function shift(&$value = null);

    /**
     * @param StringValue|string $value
     * @return StringsValue
     */
    public function push($value);

    /**
     * @param mixed $value
     * @return StringsValue
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
     * @return StringsValue
     */
    public function join(ArrayValue $other);

    /**
     * @return StringsValue
     */
    public function slice(int $offset, int $length);

    /**
     * @param callable|null $comparator function(StringValue $valueA, StringValue $valueB): int{-1, 0, 1}
     * @return StringsValue
     */
    public function diff(ArrayValue $other, ?callable $comparator = null);

    /**
     * @param callable|null $comparator function(StringValue $valueA, StringValue $valueB): int{-1, 0, 1}
     * @return StringsValue
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
     * @return StringsValue
     */
    public function notEmpty();

    // StringValue

    /**
     * @return StringsValue
     */
    public function stripTags();

    /**
     * @return StringsValue
     */
    public function trim(string $characterMask = self::TRIM_MASK);

    /**
     * @return StringsValue
     */
    public function trimRight(string $characterMask = self::TRIM_MASK);

    /**
     * @return StringsValue
     */
    public function trimLeft(string $characterMask = self::TRIM_MASK);

    /**
     * @return StringsValue
     */
    public function lower();

    /**
     * @return StringsValue
     */
    public function upper();

    /**
     * @return StringsValue
     */
    public function lowerFirst();

    /**
     * @return StringsValue
     */
    public function upperFirst();

    /**
     * @return StringsValue
     */
    public function upperWords();

    /**
     * @return StringsValue
     */
    public function padRight(int $length, string $string = ' ');

    /**
     * @return StringsValue
     */
    public function padLeft(int $length, string $string = ' ');

    /**
     * @return StringsValue
     */
    public function padBoth(int $length, string $string = ' ');

    /**
     * @return StringsValue
     */
    public function replace(string $search, string $replace);

    /**
     * @return StringsValue
     */
    public function replacePattern(string $pattern, string $replacement);

    /**
     * @return StringsValue
     */
    public function replacePatternCallback(string $pattern, callable $callback);

    /**
     * @return StringsValue
     */
    public function truncate(int $length, string $postfix = '...');

    /**
     * @return StringsValue
     */
    public function substring(int $start, ?int $length = null);

    /**
     * @return StringsValue
     */
    public function postfix(StringValue $other);
}
