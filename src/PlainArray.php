<?php

namespace GW\Value;

final class PlainArray implements ArrayValue
{
    /** @var array */
    private $items;

    public function __construct(array $items)
    {
        $this->items = array_values($items);
    }

    public function toArray(): array
    {
        return $this->items;
    }

    public function count(): int
    {
        return count($this->items);
    }
}
