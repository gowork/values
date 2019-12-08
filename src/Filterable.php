<?php

namespace GW\Value;

/**
 * @template TValue
 */
interface Filterable
{
    /**
     * @param callable(TValue $value):bool $filter
     * @return Filterable<TValue>
     */
    public function filter(callable $filter): Filterable;
}
