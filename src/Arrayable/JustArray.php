<?php declare(strict_types=1);

namespace GW\Value\Arrayable;

use GW\Value\Arrayable;
use function array_values;

/**
 * @template TValue
 * @implements Arrayable<TValue>
 */
final class JustArray implements Arrayable
{
    /** @var array<mixed,TValue> */
    private array $array;

    /**
     * @param array<mixed,TValue> $array
     */
    public function __construct(array $array)
    {
        $this->array = $array;
    }

    /** @return TValue[] */
    public function toArray(): array
    {
        return array_values($this->array);
    }
}
