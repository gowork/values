<?php declare(strict_types=1);

namespace GW\Value\Arrayable;

use GW\Value\Arrayable;
use function array_values;
use function shuffle;

final class Shuffle implements Arrayable
{
    private Arrayable $arrayable;

    public function __construct(Arrayable $arrayable)
    {
        $this->arrayable = $arrayable;
    }

    public function toArray(): array
    {
        $items = $this->arrayable->toArray();
        shuffle($items);;

        return array_values($items);
    }
}
