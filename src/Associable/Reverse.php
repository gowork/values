<?php declare(strict_types=1);

namespace GW\Value\Associable;

use GW\Value\Associable;
use function array_reverse;

/**
 * @template TKey of int|string
 * @template TValue
 * @implements Associable<TKey,TValue>
 */
final class Reverse implements Associable
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
        return array_reverse($this->associable->toAssocArray());
    }
}
