<?php declare(strict_types=1);

namespace GW\Value\Associable;

use GW\Value\Arrayable;
use GW\Value\Associable;
use function array_keys;

/**
 * @template TKey
 * @implements Arrayable<TKey>
 */
final class Keys implements Arrayable
{
    /** @var Associable<TKey,mixed> */
    private Associable $associable;

    /** @param Associable<TKey,mixed> $associable */
    public function __construct(Associable $associable)
    {
        $this->associable = $associable;
    }

    /** @return TKey[] */
    public function toArray(): array
    {
        return array_keys($this->associable->toAssocArray());
    }
}
