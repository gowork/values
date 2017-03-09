<?php

namespace GW\Value;

interface Filterable
{
    /**
     * @param callable $filter function(mixed $value): bool { ... }
     * @return Filterable
     */
    public function filter(callable $filter);
}
