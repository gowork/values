<?php

namespace GW\Value;

interface CharsValue
{
    /**
     * @return CharsValue
     */
    public function stripTags();

    /**
     * @return CharsValue
     */
    public function trim(string $characterMask = " \t\n\r\0\x0B");

    /**
     * @return CharsValue
     */
    public function trimRight(string $characterMask = " \t\n\r\0\x0B");

    /**
     * @return CharsValue
     */
    public function trimLeft(string $characterMask = " \t\n\r\0\x0B");

    /**
     * @return CharsValue
     */
    public function lower();

    /**
     * @return CharsValue
     */
    public function upper();

    /**
     * @return CharsValue
     */
    public function lowerFirst();

    /**
     * @return CharsValue
     */
    public function upperFirst();

    /**
     * @return CharsValue
     */
    public function upperWords();

    /**
     * @return CharsValue
     */
    public function padRight(int $length, string $string = ' ');

    /**
     * @return CharsValue
     */
    public function padLeft(int $length, string $string = ' ');

    /**
     * @return CharsValue
     */
    public function padBoth(int $length, string $string = ' ');

    /**
     * @return CharsValue
     */
    public function replace(string $search, string $replace);

    /**
     * @return CharsValue
     */
    public function translate(array $pairs);

    /**
     * @return CharsValue
     */
    public function replacePattern(string $pattern, string $replacement);

    /**
     * @return CharsValue
     */
    public function replacePatternCallback(string $pattern, callable $callback);

    /**
     * @return CharsValue
     */
    public function truncate(int $length, string $postfix = '...');
}
