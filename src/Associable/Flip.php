<?php declare(strict_types=1);

namespace GW\Value\Associable;

use GW\Value\Associable;
use function array_flip;

/**
 * @template TKey of int|string
 * @template TValue of int|string
 * @implements Associable<TKey,TValue>
 */
final class Flip implements Associable
{
    /** @var Associable<TValue,TKey> */
    private Associable $associable;

    /**
     * @param Associable<TValue,TKey> $associable
     */
    public function __construct(Associable $associable)
    {
        $this->associable = $associable;
    }

    /** @return array<TKey,TValue> */
    public function toAssocArray(): array
    {
        return array_flip($this->associable->toAssocArray());
    }
}
