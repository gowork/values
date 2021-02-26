<?php declare(strict_types=1);

namespace spec\GW\Value;

final class DummyEntityComparator
{
    public function __invoke(DummyEntity $entityA, DummyEntity $entityB): int
    {
        return $entityA->id <=> $entityB->id;
    }
}
