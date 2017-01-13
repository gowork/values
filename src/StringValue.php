<?php

namespace GW\Value;

interface StringValue
{
    public function length(): int;

    public function position(string $needle): ?int;

    public function lastPosition(string $needle): ?int;

    public function stripTags(): StringValue;

    public function trim(string $characterMask = " \t\n\r\0\x0B"): StringValue;

    public function trimRight(string $characterMask = " \t\n\r\0\x0B"): StringValue;

    public function trimLeft(string $characterMask = " \t\n\r\0\x0B"): StringValue;

    public function lower(): StringValue;

    public function upper(): StringValue;

    public function lowerFirst(): StringValue;

    public function upperFirst(): StringValue;

    public function upperWords(): StringValue;

    public function padRight(int $length, string $string = " "): StringValue;

    public function padLeft(int $length, string $string = " "): StringValue;

    public function padBoth(int $length, string $string = " "): StringValue;

    public function replace(string $search, string $replace): StringValue;

    public function translate(array $pairs): StringValue;

    public function replacePattern(string $pattern, string $replacement): StringValue;

    public function replacePatternCallback(string $pattern, callable $callback): StringValue;

    public function matchAllPatterns(string $pattern): ArrayValue;

    public function matchPatterns(string $pattern): ArrayValue;

    public function isMatching(string $pattern): bool;

    public function splitByPattern(string $pattern): ArrayValue;

    public function reverse(): StringValue;

    public function explode(string $delimiter): ArrayValue;

    public function truncate(int $length, string $postfix = '...'): StringValue;

    public function contains(string $substring): bool;

    public function value(): string;

    public function __toString(): string;
}
