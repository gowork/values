<?php

namespace GW\Value;

interface ArrayValue extends \IteratorAggregate, \Countable, \ArrayAccess
{
    public function map(callable $transformer): ArrayValue;

    public function filter(callable $transformer): ArrayValue;

    public function slice(int $offset, int $length): ArrayValue;

    public function sort(callable $comparator): ArrayValue;

    public function each(callable $f): ArrayValue;

    public function unique(callable $comparator = null): ArrayValue;

    public function reverse(): ArrayValue;

    public function intersect(ArrayValue $other, callable $comparator = null): ArrayValue;

    public function diff(ArrayValue $other, callable $comparator = null): ArrayValue;

    public function sum(ArrayValue $other): ArrayValue;

    public function shuffle(): ArrayValue;

    // adders and removers
    public function unshift($v): ArrayValue;

    public function shift(&$v = null): ArrayValue;

    public function push($v): ArrayValue;

    public function pop(&$v = null): ArrayValue;

    // finalizers
    public function reduce(callable $transformer, $start);

    public function toArray(): array;

    public function first();

    public function last();

    public function shiftValue();

    public function popValue();
}
