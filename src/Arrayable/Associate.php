<?php declare(strict_types=1);

namespace GW\Value\Arrayable;

use GW\Value\Arrayable;
use GW\Value\Associable;

/**
 * @template TValue
 * @implements Associable<int,TValue>
 */
final class Associate implements Associable
{
    /** @var Arrayable<TValue> */
    private Arrayable $arrayable;

    /** @param Arrayable<TValue> $arrayable */
    public function __construct(Arrayable $arrayable)
    {
        $this->arrayable = $arrayable;
    }

    /**
     * @return array<int,TValue>
     */
    public function toAssocArray(): array
    {
        return $this->arrayable->toArray();
    }
}
