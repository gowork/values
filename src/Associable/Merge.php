<?php declare(strict_types=1);

namespace GW\Value\Associable;

use GW\Value\Associable;
use function array_merge;
use function array_replace;

/**
 * @template TKey of int|string
 * @template TValue
 * @implements Associable<TKey,TValue>
 */
final class Merge implements Associable
{
    /** @var Associable<TKey,TValue> */
    private Associable $left;
    /** @var Associable<TKey,TValue> */
    private Associable $right;

    /**
     * @param Associable<TKey,TValue> $left
     * @param Associable<TKey,TValue> $right
     */
    public function __construct(Associable $left, Associable $right)
    {
        $this->left = $left;
        $this->right = $right;
    }

    /** @return array<TKey,TValue> */
    public function toAssocArray(): array
    {
        return array_merge($this->left->toAssocArray(), $this->right->toAssocArray());
    }
}
