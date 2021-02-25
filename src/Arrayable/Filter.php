<?php declare(strict_types=1);

namespace GW\Value\Arrayable;

use GW\Value\Arrayable;
use function array_filter;
use function array_values;

/**
 * @template TValue
 * @implements Arrayable<TValue>
 */
final class Filter implements Arrayable
{
    /** @var Arrayable<TValue> */
    private Arrayable $arrayable;
    /** @var callable(TValue):bool */
    private $filter;

    /**
     * @param Arrayable<TValue> $arrayable
     * @param callable(TValue):bool $filter
     */
    public function __construct(Arrayable $arrayable, callable $filter)
    {
        $this->arrayable = $arrayable;
        $this->filter = $filter;
    }

    /** @return TValue[] */
    public function toArray(): array
    {
        return array_values(array_filter($this->arrayable->toArray(), $this->filter));
    }
}
