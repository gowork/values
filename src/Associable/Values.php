<?php declare(strict_types=1);

namespace GW\Value\Associable;

use GW\Value\Arrayable;
use GW\Value\Associable;
use function array_values;

/**
 * @template TValue
 * @implements Arrayable<TValue>
 */
final class Values implements Arrayable
{
    /** @var Associable<mixed,TValue> */
    private Associable $associable;

    /** @param Associable<mixed,TValue> $associable */
    public function __construct(Associable $associable)
    {
        $this->associable = $associable;
    }

    public function toArray(): array
    {
        return array_values($this->associable->toAssocArray());
    }
}
