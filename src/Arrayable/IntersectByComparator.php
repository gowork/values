<?php declare(strict_types=1);

namespace GW\Value\Arrayable;

use GW\Value\Arrayable;
use function array_uintersect;
use function array_values;

/**
 * @template TValue
 * @implements Arrayable<TValue>
 */
final class IntersectByComparator implements Arrayable
{
    /** @var Arrayable<TValue> */
    private Arrayable $left;
    /** @var Arrayable<TValue> */
    private Arrayable $right;
    /** @var callable(TValue,TValue):int */
    private $comparator;

    /**
     * @param Arrayable<TValue> $left
     * @param Arrayable<TValue> $right
     * @param callable(TValue,TValue):int $comparator
     */
    public function __construct(Arrayable $left, Arrayable $right, callable $comparator)
    {
        $this->left = $left;
        $this->right = $right;
        $this->comparator = $comparator;
    }

    /** @return TValue[] */
    public function toArray(): array
    {
        return array_values(array_uintersect($this->left->toArray(), $this->right->toArray(), $this->comparator));
    }
}
