<?php declare(strict_types=1);

namespace GW\Value\Arrayable;

use GW\Value\Arrayable;
use function array_udiff;
use function array_values;

/**
 * @template TValue
 * @implements Arrayable<TValue>
 */
final class DiffByComparator implements Arrayable
{
    /** @var Arrayable<TValue> */
    private Arrayable $left;
    /** @var Arrayable<TValue> */
    private Arrayable $right;
    /** @var callable(TValue,TValue):int<-1,1> */
    private $comparator;

    /**
     * @param Arrayable<TValue> $left
     * @param Arrayable<TValue> $right
     * @param callable(TValue,TValue):int<-1,1> $comparator
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
        return array_values(array_udiff($this->left->toArray(), $this->right->toArray(), $this->comparator));
    }
}
