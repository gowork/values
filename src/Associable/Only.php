<?php declare(strict_types=1);

namespace GW\Value\Associable;

use GW\Value\Associable;
use function array_flip;
use function array_intersect_key;

/**
 * @template TKey
 * @template TValue
 * @implements Associable<TKey,TValue>
 */
final class Only implements Associable
{
    /** @var Associable<TKey,TValue> */
    private Associable $associable;
    /** @var string[] */
    private array $keys;

    /**
     * @param Associable<TKey,TValue> $associable
     * @param TKey ...$keys
     */
    public function __construct(Associable $associable, ...$keys)
    {
        $this->associable = $associable;
        $this->keys = $keys;
    }

    /** @return array<TKey,TValue> */
    public function toAssocArray(): array
    {
        return array_intersect_key($this->associable->toAssocArray(), array_flip($this->keys));
    }
}
