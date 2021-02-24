<?php declare(strict_types=1);

namespace GW\Value\Arrayable;

use GW\Value\Arrayable;
use function array_uintersect;
use function array_values;

final class IntersectByComparator implements Arrayable
{
    private Arrayable $left;
    private Arrayable $right;
    /** @var callable */
    private $comparator;

    public function __construct(Arrayable $left, Arrayable $right, callable $comparator)
    {
        $this->left = $left;
        $this->right = $right;
        $this->comparator = $comparator;
    }

    public function toArray(): array
    {
        return array_values(array_uintersect($this->left->toArray(), $this->right->toArray(), $this->comparator));
    }
}
