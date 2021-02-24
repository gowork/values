<?php declare(strict_types=1);

namespace GW\Value\Arrayable;

use GW\Value\Arrayable;
use function array_filter;
use function array_values;

final class Filter implements Arrayable
{
    private Arrayable $arrayable;
    /** @var callable */
    private $filter;

    public function __construct(Arrayable $arrayable, callable $filter)
    {
        $this->arrayable = $arrayable;
        $this->filter = $filter;
    }

    public function toArray(): array
    {
        return array_values(array_filter($this->arrayable->toArray(), $this->filter));
    }
}
