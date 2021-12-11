<?php declare(strict_types=1);

namespace GW\Value\Arrayable;

use GW\Value\Arrayable;
use function array_slice;

/**
 * @template TValue
 * @implements Arrayable<TValue>
 */
final class Slice implements Arrayable
{
    /** @var Arrayable<TValue> */
    private Arrayable $arrayable;
    private int $offset;
    private ?int $length;

    /** @param Arrayable<TValue> $arrayable */
    public function __construct(Arrayable $arrayable, int $offset, ?int $length = null)
    {
        $this->arrayable = $arrayable;
        $this->offset = $offset;
        $this->length = $length;
    }

    /** @return TValue[] */
    public function toArray(): array
    {
        return array_slice($this->arrayable->toArray(), $this->offset, $this->length);
    }
}
