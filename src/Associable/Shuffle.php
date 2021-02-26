<?php declare(strict_types=1);

namespace GW\Value\Associable;

use GW\Value\Associable;
use function shuffle;

/**
 * @template TKey
 * @template TValue
 * @implements Associable<TKey,TValue>
 */
final class Shuffle implements Associable
{
    /** @var Associable<TKey,TValue> */
    private Associable $associable;

    /** @param Associable<TKey,TValue> $associable */
    public function __construct(Associable $associable)
    {
        $this->associable = $associable;
    }

    /** @return array<TKey,TValue> */
    public function toAssocArray(): array
    {
        $items = $this->associable->toAssocArray();
        shuffle($items);

        return $items;
    }
}
