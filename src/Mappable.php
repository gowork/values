<?php

namespace GW\Value;

interface Mappable
{
    /**
     * @param callable $transformer function(mixed $value): mixed
     * @return Mappable
     */
    public function map(callable $transformer): Mappable;
}
