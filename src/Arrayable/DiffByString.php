<?php declare(strict_types=1);

namespace GW\Value\Arrayable;

use GW\Value\Arrayable;
use function array_diff;
use function array_values;

/**
 * @template TValue
 * @implements Arrayable<TValue>
 */
final class DiffByString implements Arrayable
{
    /** @var Arrayable<TValue> */
    private Arrayable $left;
    /** @var Arrayable<TValue> */
    private Arrayable $right;

    /**
     * @param Arrayable<TValue> $left
     * @param Arrayable<TValue> $right
     */
    public function __construct(Arrayable $left, Arrayable $right)
    {
        $this->left = $left;
        $this->right = $right;
    }

    /** @return TValue[] */
    public function toArray(): array
    {
        return array_values(array_diff($this->left->toArray(), $this->right->toArray()));
    }
}
