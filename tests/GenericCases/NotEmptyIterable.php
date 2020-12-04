<?php declare(strict_types=1);

namespace tests\GW\Value\GenericCases;

use GW\Value\Wrap;

class Id {}

class Foo
{
    public function id(): ?Id
    {
        return new Id();
    }
}

/** @return iterable<Foo> */
function all(): iterable
{
    return [new Foo()];
}

/** @param iterable<int, array<int, Id>> $all */
function iterate(iterable $all): void
{
}

function iterableValue5(): void
{
    $collection = Wrap::iterable(all());

    $chunks = $collection
        ->map(fn(Foo $document): ?Id => $document->id())
        ->filterEmpty()
        //->unique([Id::class, 'comparator'])
        ->filter(fn(Id $companyId): bool => true)
        ->slice(0, 10)
        ->chunk(250);

    iterate($chunks);
}
