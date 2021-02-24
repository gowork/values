<?php declare(strict_types=1);

namespace GW\Value\Arrayable;

use GW\Value\Arrayable;
use function array_map;

final class Map implements Arrayable
{
    private Arrayable $array;
    /** @var callable */
    private $callback;

    public function __construct(Arrayable $arrayable, callable $callback)
    {
        $this->array = $arrayable;
        $this->callback = $callback;
    }

    public function toArray(): array
    {
        return array_map($this->callback, $this->array->toArray());
    }
}
