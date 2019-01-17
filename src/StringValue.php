<?php

namespace GW\Value;

interface StringValue extends Value
{
    public const TRIM_MASK = " \t\n\r\0\x0B";

    /**
     * @param callable $transformer function(string $value): string
     * @return StringValue
     */
    public function transform(callable $transformer);

    /**
     * @return StringValue
     */
    public function stripTags();

    /**
     * @return StringValue
     */
    public function trim(string $characterMask = self::TRIM_MASK);

    /**
     * @return StringValue
     */
    public function trimRight(string $characterMask = self::TRIM_MASK);

    /**
     * @return StringValue
     */
    public function trimLeft(string $characterMask = self::TRIM_MASK);

    /**
     * @return StringValue
     */
    public function lower();

    /**
     * @return StringValue
     */
    public function upper();

    /**
     * @return StringValue
     */
    public function lowerFirst();

    /**
     * @return StringValue
     */
    public function upperFirst();

    /**
     * @return StringValue
     */
    public function upperWords();

    /**
     * @return StringValue
     */
    public function padRight(int $length, string $string = ' ');

    /**
     * @return StringValue
     */
    public function padLeft(int $length, string $string = ' ');

    /**
     * @return StringValue
     */
    public function padBoth(int $length, string $string = ' ');

    /**
     * @return StringValue
     */
    public function replace(string $search, string $replace);

    /**
     * @return StringValue
     */
    public function replacePattern(string $pattern, string $replacement);

    /**
     * @return StringValue
     */
    public function replacePatternCallback(string $pattern, callable $callback);

    /**
     * @return StringValue
     */
    public function truncate(int $length, string $postfix = '...');

    /**
     * @return StringValue
     */
    public function substring(int $start, ?int $length = null);

    /**
     * @return StringValue
     */
    public function postfix(StringValue $other);

    /**
     * @return StringValue
     */
    public function prefix(StringValue $other);

    public function length(): int;

    public function position(string $needle): ?int;

    public function positionLast(string $needle): ?int;

    /**
     * @return ArrayValue
     */
    public function matchAllPatterns(string $pattern);

    /**
     * @return StringsArray
     */
    public function matchPatterns(string $pattern);

    public function isMatching(string $pattern): bool;

    /**
     * @param string|StringValue $pattern
     */
    public function isStartingWith($pattern): bool;

    /**
     * @return StringsArray
     */
    public function splitByPattern(string $pattern);

    /**
     * @return StringsArray
     */
    public function explode(string $delimiter);

    public function contains(string $substring): bool;

    public function toString(): string;

    public function __toString(): string;
}
