<?php

namespace GW\Value;

/**
 * @template TValue
 */
interface Mappable
{
    /**
     * @template TNewValue
     * @param callable(TValue $value):TNewValue $transformer
     * @return Mappable<TNewValue>
     */
    public function map(callable $transformer): Mappable;
}
