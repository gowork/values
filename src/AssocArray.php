<?php

namespace GW\Value;

interface AssocArray extends \IteratorAggregate, \Countable, \ArrayAccess
{
    public function map(
        callable/*(mixed $value): mixed*/
        $transformer,
        callable/*(string $key): string*/
        $keyTransformer = null
    ): ArrayValue;

    public function filter(
        callable/*(mixed $value, string $key): bool*/
        $transformer
    ): ArrayValue;

    public function sort(
        callable/*(mixed $valueA, mixed $valueB): int*/
        $comparator
    ): ArrayValue;

    public function sortKeys(
        callable/*(string $keyA, string $keyB): int*/
        $comparator
    ): ArrayValue;

    public function each(
        callable/*(mixed $value, string $key): void*/
        $f
    ): ArrayValue;

    public function unique(
        ?callable/*(mixed $valueA, mixed $valueB): int*/
        $comparator = null
    ): ArrayValue;

    public function intersect(ArrayValue $other, ?callable $comparator = null): ArrayValue;

    public function diff(ArrayValue $other, ?callable $comparator = null): ArrayValue;

    public function sum(ArrayValue $other): ArrayValue;

    // adders and removers
    /**
     * @param mixed $v
     */
    public function with(string $key, $v): ArrayValue;

    public function without(string $key): ArrayValue;

    /**
     * @param mixed $v
     */
    public function withoutElement($v): ArrayValue;

    // finalizers
    /**
     * @param mixed $start
     * @return mixed
     */
    public function reduce(
        callable/*(mixed $value, mixed $reduced, string $key): mixed*/
        $transformer,
        $start
    );

    /**
     * @return mixed[]
     */
    public function toArray(): array;

    /**
     * @return string[]|ArrayValue
     */
    public function keys(): ArrayValue;

    public function values(): ArrayValue;

    /**
     * @return mixed
     */
    public function get(string $key);

    public function has(string $key): bool;
}
