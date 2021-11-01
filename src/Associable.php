<?php declare(strict_types=1);

namespace GW\Value;

/**
 * @template TKey of int|string
 * @template TValue
 */
interface Associable
{
    /**
     * @phpstan-return array<TKey, TValue>
     */
    public function toAssocArray(): array;
}
