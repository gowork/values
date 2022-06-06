<?php declare(strict_types=1);

namespace tests\GW\Value\GenericCases;

use GW\Value\IterableValue;
use GW\Value\Wrap;
use function random_int;

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

class RequireIterable
{
    /** @param iterable<int> $it */
    public function functionRequireIterable(iterable $it): void
    {
    }
    /** @param iterable<int,int> $it */
    public function functionRequireIterable2(iterable $it): void
    {
    }
    /** @param IterableValue<int,int> $it */
    public function functionRequireIterable3(IterableValue $it): void
    {
    }
    /** @param array<int,Foo> $it */
    public function functionRequireArray(array $it): void
    {
    }
}

$ri = new RequireIterable();
$ri->functionRequireIterable(Wrap::iterable([null, 1])->filterEmpty());
$ri->functionRequireIterable2(Wrap::iterable([null, 1])->filterEmpty());
$ri->functionRequireIterable3(Wrap::iterable([null, 1])->filterEmpty());

$ri->functionRequireIterable(Wrap::iterable([2, 1, 3])->map(fn(int $x): ?int => random_int(0, 2) ?: null)->filterEmpty());
$ri->functionRequireIterable2(Wrap::iterable([null, 1])->filterEmpty());
$ri->functionRequireIterable3(Wrap::iterable([null, 1])->filterEmpty());

$ia = Wrap::iterable([2, 1, 3])->map(fn(int $x): ?Foo => random_int(0, 2) ? new Foo() : null)->filterEmpty()->toArray();
$ri->functionRequireArray($ia);
