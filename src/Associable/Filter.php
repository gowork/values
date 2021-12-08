<?php declare(strict_types=1);

namespace GW\Value\Associable;

use GW\Value\Associable;
use function array_filter;

/**
 * @template TKey of int|string
 * @template TValue
 * @implements Associable<TKey,TValue>
 */
final class Filter implements Associable
{
    /** @var Associable<TKey,TValue> */
    private Associable $associable;
    /** @var callable(TValue):bool */
    private $filter;

    /**
     * @param Associable<TKey,TValue> $associable
     * @param callable(TValue):bool $filter
     */
    public function __construct(Associable $associable, callable $filter)
    {
        $this->associable = $associable;
        $this->filter = $filter;
    }

    public function toAssocArray(): array
    {
        return array_filter($this->associable->toAssocArray(), $this->filter);
    }
}
