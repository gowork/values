<?php declare(strict_types=1);

namespace GW\Value\Arrayable;

use GW\Value\Arrayable;
use function array_unique;

final class UniqueByComparator implements Arrayable
{
    private Arrayable $arrayable;
    /** @var callable */
    private $comparator;

    public function __construct(Arrayable $arrayable, callable $comparator)
    {
        $this->arrayable = $arrayable;
        $this->comparator = $comparator;
    }

    public function toArray(): array
    {
        $result = [];
        $comparator = $this->comparator;

        foreach ($this->arrayable->toArray() as $valueA) {
            foreach ($result as $valueB) {
                if ($comparator($valueA, $valueB) === 0) {
                    // item already in result
                    continue 2;
                }
            }

            $result[] = $valueA;
        }

        return $result;
    }
}
