<?php declare(strict_types=1);

namespace GW\Value\Associable;

use GW\Value\Associable;

/**
 * @template TKey
 * @template TValue
 * @implements Associable<TKey,TValue>
 */
final class JustAssoc implements Associable
{
    /** @var array<TKey,TValue> */
    private array $array;

    /** @param array<TKey,TValue> $array */
    public function __construct(array $array)
    {
        $this->array = $array;
    }

    /** @return array<TKey,TValue> */
    public function toAssocArray(): array
    {
        return $this->array;
    }
}
