<?php

namespace spec\GW\Value;

use GW\Value\InfiniteIterableValue;
use GW\Value\PlainArray;
use GW\Value\Sorts;
use GW\Value\Wrap;
use PhpSpec\Exception\Example\FailureException;
use PhpSpec\ObjectBehavior;

final class InfiniteIterableValueSpec extends ObjectBehavior
{
    function it_can_be_converted_to_array_value()
    {
        $items = ['item 1', 'item 2', 'item 3'];
        $this->beConstructedWith($items);
        $this->toArrayValue()->shouldBeLike(Wrap::array($items));
    }

    function it_returns_items()
    {
        $items = ['item 1', 'item 2', 'item 3'];
        $this->beConstructedWith($items);
        $this->toArray()->shouldReturn($items);
    }

    function it_maps_string_items_with_closure()
    {
        $this->beConstructedWith(['item 1', 'item 2', 'item 3']);

        $mapped = $this->map(function (string $item): string {
            return $item . ' mapped';
        });

        $mapped->shouldNotBe($this);
        $mapped->toArray()->shouldBeLike(['item 1 mapped', 'item 2 mapped', 'item 3 mapped']);
    }

    function it_maps_items_with_php_callable()
    {
        $this->beConstructedWith(['100', '50.12', '', true, false]);

        $mapped = $this->map('intval');

        $mapped->shouldNotBe($this);
        $mapped->toArray()->shouldBeLike([100, 50, 0, 1, 0]);
    }

    function it_maps_with_flattening_with_array_transformer()
    {
        $this->shouldFlatMapBandMembersWith(
            function (array $band): array {
                return $band['members'];
            }
        );
    }

    function it_maps_with_flattening_with_iterator_transformer()
    {
        $this->shouldFlatMapBandMembersWith(
            function (array $band): \Iterator {
                return new \ArrayIterator($band['members']);
            }
        );
    }

    function it_can_flat_map_empty_array()
    {
        $this->beConstructedWith([[], []]);

        $transformer = function (): array {
            return [];
        };

        $this->flatMap($transformer)->toArray()->shouldBeLike([]);
    }

    private function shouldFlatMapBandMembersWith(callable $mapper): void
    {
        $this->beConstructedWith([
            [
                'band' => 'The Beatles',
                'members' => ['John', 'Paul', 'George', 'Ringo'],
            ],
            [
                'band' => 'Rolling Stones',
                'members' => ['Mick', 'Keith', 'Ron', 'Charlie'],
            ],
            [
                'band' => 'Led Zeppelin',
                'members' => ['Robert', 'Jimmy', 'John Paul', 'John'],
            ],
        ]);

        $legends = $this->flatMap($mapper);
        $legends->shouldNotBe($this);
        $legends->toArray()->shouldBeLike(
            [
                'John',
                'Paul',
                'George',
                'Ringo',
                'Mick',
                'Keith',
                'Ron',
                'Charlie',
                'Robert',
                'Jimmy',
                'John Paul',
                'John',
            ]
        );
    }
    
    function it_filters_items_with_closure()
    {
        $this->beConstructedWith([
            'Because I`m bad',
            'You know I`m bad',
            'And the whole world has to',
            'Answer right now',
            'Just to tell you once again',
            'Who`s bad',
        ]);

        $notBad = $this->filter(function (string $line): bool {
            return strpos($line, 'bad') === false;
        });

        $notBad->shouldNotBe($this);
        $notBad->toArray()->shouldBeLike(
            [
                'And the whole world has to',
                'Answer right now',
                'Just to tell you once again',
            ]
        );
    }

    function it_filters_with_php_callable()
    {
        $this->beConstructedWith(['Hello', new \stdClass(), '1410', 3.14, true]);

        $numbers = $this->filter('is_numeric');

        $numbers->shouldNotBe($this);
        $numbers->toArray()->shouldBeLike(['1410', 3.14]);
    }

    function it_can_call_some_action_on_each_item(CallableMock $callable)
    {
        $this->beConstructedWith(['item 1', 'item 2', 'item 3']);

        $this->each($callable);

        $callable->__invoke('item 1')->shouldHaveBeenCalled();
        $callable->__invoke('item 2')->shouldHaveBeenCalled();
        $callable->__invoke('item 3')->shouldHaveBeenCalled();
    }

    function it_joins_with_other_array()
    {
        $this->beConstructedWith(['a 1', 'a 2', 'a 3']);

        $joined = $this->join(new InfiniteIterableValue(['b 1', 'b 2', 'b 3']));

        $joined->shouldNotBe($this);
        $joined->toArray()->shouldBeLike(['a 1', 'a 2', 'a 3', 'b 1', 'b 2', 'b 3']);
    }

    function it_slices_given_part()
    {
        $this->beConstructedWith(['item 1', 'item 2', 'item 3', 'item 4', 'item 5', 'item 6']);

        $this->slice(0, 1)->shouldNotBe($this);
        $this->slice(0, 1)->toArray()->shouldBeLike(['item 1']);
        $this->slice(1, 4)->toArray()->shouldBeLike(['item 2', 'item 3', 'item 4', 'item 5']);
        $this->slice(5, 1)->toArray()->shouldBeLike(['item 6']);
    }

    function it_can_return_clone_with_unique_values_comparing_them_as_strings()
    {
        $this->beConstructedWith(['item 1', 'item 2', 'item 2', 'item 3', 'item 4', 'item 4', 'item 5']);

        $unique = $this->unique();

        $unique->shouldNotBe($this);
        $unique->toArray()->shouldBeLike(['item 1', 'item 2', 'item 3', 'item 4', 'item 5']);
    }

    function it_can_return_clone_with_unique_values_comparing_them_using_comparator()
    {
        $this->beConstructedWith([
            new DummyEntity(1, 'Joe'),
            new DummyEntity(1, 'Joey'),
            new DummyEntity(2, 'William'),
            new DummyEntity(2, 'Will'),
            new DummyEntity(3, 'Jack'),
            new DummyEntity(4, 'Averell'),
            new DummyEntity(4, 'Goofy'),
        ]);

        $comparator = $this->entityComparator();

        $unique = $this->unique($comparator);

        $unique->shouldNotBe($this);
        $unique->toArray()->shouldBeLike(
            [
                new DummyEntity(1, 'Joe'),
                new DummyEntity(2, 'William'),
                new DummyEntity(3, 'Jack'),
                new DummyEntity(4, 'Averell'),
            ]
        );
    }

    function it_returns_diff_using_string_comparison_by_default()
    {
        $items = ['item 1', 'item 2', 'item 3', 'item 4'];
        $this->beConstructedWith($items);

        $this->diff(new PlainArray([]))->toArray()->shouldBeLike(['item 1', 'item 2', 'item 3', 'item 4']);
        $this->diff(new PlainArray($items))->toArray()->shouldBeLike([]);
        $this->diff(new PlainArray(['item 1', 'item 4']))->toArray()->shouldBeLike(['item 2', 'item 3']);
        $this->diff(new PlainArray(['item 2', 'item 3']))->toArray()->shouldBeLike(['item 1', 'item 4']);
    }

    function it_returns_diff_using_provided_comparator()
    {
        $joe = new DummyEntity(1, 'Joe');
        $william = new DummyEntity(2, 'William');
        $jack = new DummyEntity(3, 'Jack');
        $averell = new DummyEntity(4, 'Averell');

        $this->beConstructedWith([$joe, $william, $jack, $averell]);

        $comparator = $this->entityComparator();

        $this->diff(new PlainArray([]), $comparator)->toArray()->shouldBeLike([$joe, $william, $jack, $averell]);
        $this->diff(new PlainArray([new DummyEntity(5, 'Ma')]), $comparator)
            ->toArray()->shouldBeLike([$joe, $william, $jack, $averell]);
        $this->diff(new PlainArray([$joe]), $comparator)->toArray()->shouldBeLike([$william, $jack, $averell]);
        $this->diff(new PlainArray([$averell]), $comparator)->toArray()->shouldBeLike([$joe, $william, $jack]);
        $this->diff(new PlainArray([$william, $jack]), $comparator)->toArray()->shouldBeLike([$joe, $averell]);
        $this->diff(new PlainArray([$joe, $william, $jack, $averell]), $comparator)->toArray()->shouldBeLike([]);
    }

    function it_returns_intersection_using_string_comparison_by_default()
    {
        $items = ['item 1', 'item 2', 'item 3', 'item 4'];
        $this->beConstructedWith($items);

        $this->intersect(new PlainArray([]))->toArray()->shouldBeLike([]);
        $this->intersect(new PlainArray($items))->toArray()->shouldBeLike(['item 1', 'item 2', 'item 3', 'item 4']);
        $this->intersect(new PlainArray(['item 1', 'item 4', 'item 5']))
            ->toArray()->shouldBeLike(['item 1', 'item 4']);
        $this->intersect(new PlainArray(['item 0', 'item 2', 'item 3', 'item 5']))
            ->toArray()->shouldBeLike(['item 2', 'item 3']);
    }

    function it_returns_intersection_using_provided_comparator()
    {
        $joe = new DummyEntity(1, 'Joe');
        $william = new DummyEntity(2, 'William');
        $jack = new DummyEntity(3, 'Jack');
        $averell = new DummyEntity(4, 'Averell');

        $this->beConstructedWith([$joe, $william, $jack, $averell]);

        $comparator = $this->entityComparator();

        $this->intersect(new PlainArray([]), $comparator)->toArray()->shouldBeLike([]);
        $this->intersect(new PlainArray([$joe, $william, $jack, $averell]), $comparator)->toArray()->shouldBeLike([$joe, $william, $jack, $averell]);
        $this->intersect(new PlainArray([new DummyEntity(5, 'Ma')]), $comparator)
            ->toArray()->shouldBeLike([]);
        $this->intersect(new PlainArray([$joe]), $comparator)->toArray()->shouldBeLike([$joe]);
        $this->intersect(new PlainArray([$averell]), $comparator)->toArray()->shouldBeLike([$averell]);
        $this->intersect(new PlainArray([$william, $jack]), $comparator)
            ->toArray()->shouldBeLike([$william, $jack]);
        $this->intersect(new PlainArray([$joe, $william, $jack, $averell]), $comparator)
            ->toArray()->shouldBeLike([$joe, $william, $jack, $averell]);
    }

    function it_prepends_value()
    {
        $this->beConstructedWith(['item 1', 'item 2', 'item 3']);

        $clone = $this->unshift('item 0');
        $clone->shouldNotBe($this);
        $clone->toArray()->shouldBeLike(['item 0', 'item 1', 'item 2', 'item 3']);
    }

    function it_pushes_value_to_the_end()
    {
        $this->beConstructedWith(['item 1', 'item 2', 'item 3']);

        $clone = $this->push('item 4');
        $clone->shouldNotBe($this);
        $clone->toArray()->shouldBeLike(['item 1', 'item 2', 'item 3', 'item 4']);
    }

    function it_reduces_array_of_numbers()
    {
        $this->beConstructedWith([10, 20, 50, 100]);

        $sum = function (int $sum, int $value): int {
            return $sum + $value;
        };

        $this->reduce($sum, 0)->shouldReturn(180);
    }

    function it_reduces_array_of_strings()
    {
        $this->beConstructedWith(['She', 'sells', 'sea', 'shells', 'by', 'the', 'sea', 'shore']);

        $join = function (string $reduced, string $value): string {
            return trim($reduced . ' ' . $value);
        };

        $this->reduce($join, '')->shouldReturn('She sells sea shells by the sea shore');
    }

    function it_returns_array_of_items()
    {
        $items = ['item 1', 'item 2', 'item 3'];
        $this->beConstructedWith($items);

        $this->toArray()->shouldReturn($items);
    }

    function it_is_iterable()
    {
        $this->beConstructedWith([]);
        $this->shouldImplement(\IteratorAggregate::class);
        $this->getIterator()->shouldHaveType(\Iterator::class);
    }

    private function entityComparator(): \Closure
    {
        return function (DummyEntity $entityA, DummyEntity $entityB): int {
            return $entityA->id <=> $entityB->id;
        };
    }
}
