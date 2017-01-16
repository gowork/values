<?php

namespace GW\Value;

interface ArrayValue extends \IteratorAggregate, \Countable, \ArrayAccess
{
    public function map(
        callable/*(mixed $value): mixed*/
        $transformer
    ): ArrayValue;

    public function filter(
        callable/*(mixed $value): bool*/
        $transformer
    ): ArrayValue;

    public function slice(int $offset, int $length): ArrayValue;

    public function sort(
        callable/*(mixed $valueA, mixed $valueB): int*/
        $comparator
    ): ArrayValue;

    public function each(
        callable/*(mixed $value): void*/
        $f
    ): ArrayValue;

    public function unique(
        ?callable/*(mixed $valueA, mixed $valueB): int*/
        $comparator = null
    ): ArrayValue;

    public function reverse(): ArrayValue;

    public function intersect(
        ArrayValue $other,
        ?callable/*(mixed $valueA, mixed $valueB): int*/
        $comparator = null
    ): ArrayValue;

    public function diff(
        ArrayValue $other,
        ?callable/*(mixed $valueA, mixed $valueB): int*/
        $comparator = null
    ): ArrayValue;

    public function join(ArrayValue $other): ArrayValue;

    public function shuffle(): ArrayValue;

    // adders and removers
    /**
     * @param mixed $v
     */
    public function unshift($v): ArrayValue;

    /**
     * @param mixed $v
     */
    public function shift(&$v = null): ArrayValue;

    /**
     * @param mixed $v
     */
    public function push($v): ArrayValue;

    /**
     * @param mixed $v
     */
    public function pop(&$v = null): ArrayValue;

    // finalizers

    /**
     * @param mixed $start
     * @return mixed
     */
    public function reduce(
        callable/*(mixed $value, mixed $reduced): mixed*/
        $transformer,
        $start
    );

    /**
     * @return mixed[]
     */
    public function toArray(): array;

    public function sum(): int;

    /**
     * @return mixed
     */
    public function first();

    /**
     * @return mixed
     */
    public function last();
}
