<?php

namespace GW\Value;

/**
 * @template TValue
 */
interface Filterable
{
    /**
     * @phpstan-param callable(TValue):bool $filter
     * @phpstan-return Filterable<TValue>
     */
    public function filter(callable $filter): Filterable;
}
