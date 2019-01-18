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
     * @param string|StringValue $characterMask
     */
    public function trim($characterMask = self::TRIM_MASK);

    /**
     * @return StringValue
     * @param string|StringValue $characterMask
     */
    public function trimRight($characterMask = self::TRIM_MASK);

    /**
     * @return StringValue
     * @param string|StringValue $characterMask
     */
    public function trimLeft($characterMask = self::TRIM_MASK);

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
     * @param string|StringValue $string
     */
    public function padRight(int $length, $string = ' ');

    /**
     * @return StringValue
     * @param string|StringValue $string
     */
    public function padLeft(int $length, $string = ' ');

    /**
     * @return StringValue
     * @param string|StringValue $string
     */
    public function padBoth(int $length, $string = ' ');

    /**
     * @return StringValue
     * @param string|StringValue $search
     * @param string|StringValue $replace
     */
    public function replace($search, $replace);

    /**
     * @return StringValue
     * @param string|StringValue $pattern
     * @param string|StringValue $replacement
     */
    public function replacePattern($pattern, $replacement);

    /**
     * @return StringValue
     * @param string|StringValue $pattern
     */
    public function replacePatternCallback($pattern, callable $callback);

    /**
     * @return StringValue
     * @param string|StringValue $postfix
     */
    public function truncate(int $length, $postfix = '...');

    /**
     * @return StringValue
     */
    public function substring(int $start, ?int $length = null);

    /**
     * @return StringValue
     * @param string|StringValue $other
     */
    public function postfix($other);

    /**
     * @return StringValue
     * @param string|StringValue $other
     */
    public function prefix($other);

    public function length(): int;

    /**
     * @param string|StringValue $needle
     */
    public function position($needle): ?int;

    /**
     * @param string|StringValue $needle
     */
    public function positionLast($needle): ?int;

    /**
     * @return ArrayValue
     * @param string|StringValue $pattern
     */
    public function matchAllPatterns($pattern);

    /**
     * @return StringsArray
     * @param string|StringValue $pattern
     */
    public function matchPatterns($pattern);

    /**
     * @param string|StringValue $pattern
     */
    public function isMatching($pattern): bool;

    /**
     * @param string|StringValue $pattern
     */
    public function startsWith($pattern): bool;

    /**
     * @param string|StringValue $pattern
     */
    public function endsWith($pattern): bool;

    /**
     * @return StringsArray
     * @param string|StringValue $pattern
     */
    public function splitByPattern($pattern);

    /**
     * @return StringsArray
     * @param string|StringValue $delimiter
     */
    public function explode($delimiter);

    /**
     * @param string|StringValue $substring
     */
    public function contains($substring): bool;

    public function toString(): string;

    public function __toString(): string;
}
