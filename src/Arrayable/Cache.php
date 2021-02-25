<?php declare(strict_types=1);

namespace GW\Value\Arrayable;

use GW\Value\Arrayable;

final class Cache implements Arrayable
{
    private ?Arrayable $arrayable;
    private array $array;

    public function __construct(Arrayable $arrayable)
    {
        $this->arrayable = $arrayable;
    }

    public function toArray(): array
    {
        if ($this->arrayable !== null) {
            $this->array = $this->arrayable->toArray();
            $this->arrayable = null;
        }

        return $this->array;
    }
}
