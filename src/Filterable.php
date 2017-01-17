<?php

namespace GW\Value;

interface Filterable
{
    /**
     * @param callable $transformer function(mixed $value): bool { ... }
     * @return Filterable
     */
    public function filter(callable $transformer);
}
