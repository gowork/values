<?php declare(strict_types=1);

namespace GW\Value;

/**
 * @template TValue
 */
interface Arrayable
{
    /**
     * @return TValue[]
     */
    public function toArray(): array;
}
