<?php declare(strict_types=1);

namespace GW\Value\Arrayable;

use GW\Value\Arrayable;
use function array_values;
use function uasort;

final class Sort implements Arrayable
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
        $items = $this->arrayable->toArray();
        uasort($items, $this->comparator);

        return array_values($items);
    }
}
