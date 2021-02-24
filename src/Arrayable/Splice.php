<?php declare(strict_types=1);

namespace GW\Value\Arrayable;

use GW\Value\Arrayable;
use function array_splice;

final class Splice implements Arrayable
{
    private Arrayable $arrayable;
    private int $offset;
    private int $length;
    private Arrayable $replacement;

    public function __construct(Arrayable $arrayable, int $offset, int $length, Arrayable $replacement)
    {
        $this->arrayable = $arrayable;
        $this->offset = $offset;
        $this->length = $length;
        $this->replacement = $replacement;
    }

    public function toArray(): array
    {
        $items = $this->arrayable->toArray();
        array_splice($items, $this->offset, $this->length, $this->replacement->toArray());

        return $items;
    }
}
