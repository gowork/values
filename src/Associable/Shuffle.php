<?php declare(strict_types=1);

namespace GW\Value\Associable;

use GW\Value\Associable;
use function shuffle;

/**
 * @template TKey of int|string
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
        $old = $this->associable->toAssocArray();
        $new = [];

        $keys = array_keys($old);
        shuffle($keys);

        foreach ($keys as $key) {
            $new[$key] = $old[$key];
        }

        return $new;
    }
}
