<?php declare(strict_types=1);

namespace GW\Value\Arrayable;

use GW\Value\Arrayable;
use function array_splice;

/**
 * @template TValue
 * @implements Arrayable<TValue>
 */
final class Splice implements Arrayable
{
    /** @var Arrayable<TValue> */
    private Arrayable $arrayable;
    private int $offset;
    private int $length;
    /** @var Arrayable<TValue> */
    private Arrayable $replacement;

    /**
     * @param Arrayable<TValue> $arrayable
     * @param Arrayable<TValue> $replacement
     */
    public function __construct(Arrayable $arrayable, int $offset, int $length, Arrayable $replacement)
    {
        $this->arrayable = $arrayable;
        $this->offset = $offset;
        $this->length = $length;
        $this->replacement = $replacement;
    }

    /** @return TValue[] */
    public function toArray(): array
    {
        $items = $this->arrayable->toArray();
        array_splice($items, $this->offset, $this->length, $this->replacement->toArray());

        return $items;
    }
}
