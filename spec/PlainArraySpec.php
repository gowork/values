<?php

namespace spec\GW\Value;

use GW\Value\PlainArray;
use GW\Value\Sorts;
use GW\Value\Wrap;
use PhpSpec\Exception\Example\FailureException;
use PhpSpec\ObjectBehavior;

final class PlainArraySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->beConstructedWith([]);
        $this->shouldHaveType(PlainArray::class);
    }

    function it_is_countable()
    {
        $this->beConstructedWith(['item 1', 'item 2']);
        $this->count()->shouldReturn(2);
    }

    function it_returns_items()
    {
        $items = ['item 1', 'item 2', 'item 3'];
        $this->beConstructedWith($items);
        $this->toArray()->shouldReturn($items);
    }

    function it_removes_keys_from_associative_array()
    {
        $items = ['item 1' => 'item 1', '2' => 'item 2', 'three' => 'item 3'];
        $this->beConstructedWith($items);
        $this->toArray()->shouldReturn(['item 1', 'item 2', 'item 3']);
    }

    function it_maps_string_items_with_closure()
    {
        $this->beConstructedWith(['item 1', 'item 2', 'item 3']);

        $mapped = $this->map(function (string $item): string {
            return $item . ' mapped';
        });

        $mapped->shouldNotBe($this);
        $mapped->shouldBeLike(new PlainArray(['item 1 mapped', 'item 2 mapped', 'item 3 mapped']));
    }

    function it_maps_items_with_php_callable()
    {
        $this->beConstructedWith(['100', '50.12', '', true, false]);

        $mapped = $this->map('intval');

        $mapped->shouldNotBe($this);
        $mapped->shouldBeLike(new PlainArray([100, 50, 0, 1, 0]));
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

    function it_throws_InvalidArgumentException_when_flatMap_transformer_is_not_iterable()
    {
        $this->beConstructedWith([['a'], ['b'], ['c']]);

        $invalidTransformer = function (array $item): string {
            return $item[0];
        };

        $this->shouldThrow(\InvalidArgumentException::class)->during('flatMap', [$invalidTransformer]);
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
        $legends->shouldBeLike(
            new PlainArray([
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
            ])
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
        $notBad->shouldBeLike(
            new PlainArray([
                'And the whole world has to',
                'Answer right now',
                'Just to tell you once again',
            ])
        );
    }

    function it_filters_with_php_callable()
    {
        $this->beConstructedWith(['Hello', new \stdClass(), '1410', 3.14, true]);

        $numbers = $this->filter('is_numeric');

        $numbers->shouldNotBe($this);
        $numbers->shouldBeLike(new PlainArray(['1410', 3.14]));
    }

    function it_sorts_numbers_ascending()
    {
        $this->beConstructedWith([4, 1, 8, 2, 9, 3, 5, 7, 6]);

        $sorted = $this->sort(Sorts::asc());
        $sorted->shouldBeLike(new PlainArray([1, 2, 3, 4, 5, 6, 7, 8, 9]));
    }

    function it_sorts_numbers_descending()
    {
        $this->beConstructedWith([4, 1, 8, 2, 9, 3, 5, 7, 6]);

        $sorted = $this->sort(Sorts::desc());
        $sorted->shouldBeLike(new PlainArray([9, 8, 7, 6, 5, 4, 3, 2, 1]));
    }

    function it_sorts_strings_ascending()
    {
        $this->beConstructedWith(['def', 'efg', 'abc', 'xyz', 'zzz']);

        $sorted = $this->sort(Sorts::asc());
        $sorted->shouldBeLike(new PlainArray(['abc', 'def', 'efg', 'xyz', 'zzz']));
    }

    function it_sorts_strings_descending()
    {
        $this->beConstructedWith(['def', 'efg', 'abc', 'xyz', 'zzz']);

        $sorted = $this->sort(Sorts::desc());
        $sorted->shouldBeLike(new PlainArray(['zzz', 'xyz', 'efg', 'def', 'abc']));
    }

    function it_can_call_some_action_on_each_item(CallableMock $callable)
    {
        $this->beConstructedWith(['item 1', 'item 2', 'item 3']);

        $this->each($callable);

        $callable->__invoke('item 1')->shouldHaveBeenCalled();
        $callable->__invoke('item 2')->shouldHaveBeenCalled();
        $callable->__invoke('item 3')->shouldHaveBeenCalled();
    }

    function it_reverses_items_order()
    {
        $this->beConstructedWith(['item 1', 'item 2', 'item 3']);

        $reversed = $this->reverse();

        $reversed->shouldNotBe($this);
        $reversed->shouldBeLike(new PlainArray(['item 3', 'item 2', 'item 1']));
    }

    function it_joins_with_other_array()
    {
        $this->beConstructedWith(['a 1', 'a 2', 'a 3']);

        $joined = $this->join(new PlainArray(['b 1', 'b 2', 'b 3']));

        $joined->shouldNotBe($this);
        $joined->shouldBeLike(new PlainArray(['a 1', 'a 2', 'a 3', 'b 1', 'b 2', 'b 3']));
    }

    function it_slices_given_part()
    {
        $this->beConstructedWith(['item 1', 'item 2', 'item 3', 'item 4', 'item 5', 'item 6']);

        $this->slice(0, 1)->shouldNotBe($this);
        $this->slice(0, 1)->shouldBeLike(new PlainArray(['item 1']));
        $this->slice(1, 4)->shouldBeLike(new PlainArray(['item 2', 'item 3', 'item 4', 'item 5']));
        $this->slice(5, 1)->shouldBeLike(new PlainArray(['item 6']));
    }

    function it_can_return_clone_with_unique_values_comparing_them_as_strings()
    {
        $this->beConstructedWith(['item 1', 'item 2', 'item 2', 'item 3', 'item 4', 'item 4', 'item 5']);

        $unique = $this->unique();

        $unique->shouldNotBe($this);
        $unique->shouldBeLike(new PlainArray(['item 1', 'item 2', 'item 3', 'item 4', 'item 5']));
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
        $unique->shouldBeLike(
            new PlainArray([
                new DummyEntity(1, 'Joe'),
                new DummyEntity(2, 'William'),
                new DummyEntity(3, 'Jack'),
                new DummyEntity(4, 'Averell'),
            ])
        );
    }

    function it_returns_diff_using_string_comparison_by_default()
    {
        $items = ['item 1', 'item 2', 'item 3', 'item 4'];
        $this->beConstructedWith($items);

        $this->diff(new PlainArray([]))->shouldBe($this);
        $this->diff(new PlainArray($items))->shouldBeLike(new PlainArray([]));
        $this->diff(new PlainArray(['item 1', 'item 4']))->shouldBeLike(new PlainArray(['item 2', 'item 3']));
        $this->diff(new PlainArray(['item 2', 'item 3']))->shouldBeLike(new PlainArray(['item 1', 'item 4']));
    }

    function it_returns_diff_using_provided_comparator()
    {
        $joe = new DummyEntity(1, 'Joe');
        $william = new DummyEntity(2, 'William');
        $jack = new DummyEntity(3, 'Jack');
        $averell = new DummyEntity(4, 'Averell');

        $this->beConstructedWith([$joe, $william, $jack, $averell]);

        $comparator = $this->entityComparator();

        $this->diff(new PlainArray([]), $comparator)->shouldBe($this);
        $this->diff(new PlainArray([new DummyEntity(5, 'Ma')]), $comparator)
            ->shouldBeLike(new PlainArray([$joe, $william, $jack, $averell]));
        $this->diff(new PlainArray([$joe]), $comparator)->shouldBeLike(new PlainArray([$william, $jack, $averell]));
        $this->diff(new PlainArray([$averell]), $comparator)->shouldBeLike(new PlainArray([$joe, $william, $jack]));
        $this->diff(new PlainArray([$william, $jack]), $comparator)->shouldBeLike(new PlainArray([$joe, $averell]));
        $this->diff(new PlainArray([$joe, $william, $jack, $averell]), $comparator)->shouldBeLike(new PlainArray([]));
    }

    function it_returns_intersection_using_string_comparison_by_default()
    {
        $items = ['item 1', 'item 2', 'item 3', 'item 4'];
        $this->beConstructedWith($items);

        $this->intersect(new PlainArray([]))->shouldBeLike(new PlainArray([]));
        $this->intersect(new PlainArray($items))->shouldBe($this);
        $this->intersect(new PlainArray(['item 1', 'item 4', 'item 5']))
            ->shouldBeLike(new PlainArray(['item 1', 'item 4']));
        $this->intersect(new PlainArray(['item 0', 'item 2', 'item 3', 'item 5']))
            ->shouldBeLike(new PlainArray(['item 2', 'item 3']));
    }

    function it_returns_intersection_using_provided_comparator()
    {
        $joe = new DummyEntity(1, 'Joe');
        $william = new DummyEntity(2, 'William');
        $jack = new DummyEntity(3, 'Jack');
        $averell = new DummyEntity(4, 'Averell');

        $this->beConstructedWith([$joe, $william, $jack, $averell]);

        $comparator = $this->entityComparator();

        $this->intersect(new PlainArray([]), $comparator)->shouldBeLike(new PlainArray([]));
        $this->intersect(new PlainArray([$joe, $william, $jack, $averell]), $comparator)->shouldBe($this);
        $this->intersect(new PlainArray([new DummyEntity(5, 'Ma')]), $comparator)
            ->shouldBeLike(new PlainArray([]));
        $this->intersect(new PlainArray([$joe]), $comparator)->shouldBeLike(new PlainArray([$joe]));
        $this->intersect(new PlainArray([$averell]), $comparator)->shouldBeLike(new PlainArray([$averell]));
        $this->intersect(new PlainArray([$william, $jack]), $comparator)
            ->shouldBeLike(new PlainArray([$william, $jack]));
        $this->intersect(new PlainArray([$joe, $william, $jack, $averell]), $comparator)
            ->shouldBeLike(new PlainArray([$joe, $william, $jack, $averell]));
    }

    function it_shuffles_items_returning_clone()
    {
        $this->beConstructedWith([1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]);

        $shuffle = $this->shuffle();
        $shuffle->shouldNotBe($this);
        $shuffle->count()->shouldBeEqualTo($this->count());
        // Let`s trust it is shuffled...
    }

    function it_prepends_value()
    {
        $this->beConstructedWith(['item 1', 'item 2', 'item 3']);

        $clone = $this->unshift('item 0');
        $clone->shouldNotBe($this);
        $clone->shouldBeLike(new PlainArray(['item 0', 'item 1', 'item 2', 'item 3']));
    }

    function it_shifts_value_of_the_beginning_and_returns_reduced_array()
    {
        $this->beConstructedWith(['item 1', 'item 2', 'item 3']);

        $clone = $this->shift();
        $clone->shouldNotBe($this);
        $clone->shouldBeLike(new PlainArray(['item 2', 'item 3']));
    }

    function it_shifts_value_of_the_beginning_and_assigns_to_variable()
    {
        // No idea how to force PhpSpec to pass variable reference,
        // so here`s a little workaround.
        $items = ['item 1', 'item 2', 'item 3'];
        $this->beConstructedWith($items);
        $clone = new PlainArray($items);

        $reduced= $this->shift();
        $reduced->shouldBeLike($clone->shift($item));

        if ($item !== 'item 1') {
            throw new FailureException('Shifted value should be assigned to provided variable');
        }

        $reduced->count()->shouldReturn(2);
    }

    function it_pushes_value_to_the_end()
    {
        $this->beConstructedWith(['item 1', 'item 2', 'item 3']);

        $clone = $this->push('item 4');
        $clone->shouldNotBe($this);
        $clone->shouldBeLike(new PlainArray(['item 1', 'item 2', 'item 3', 'item 4']));
    }

    function it_pops_value_of_the_end_and_returns_reduced_array()
    {
        $this->beConstructedWith(['item 1', 'item 2', 'item 3']);

        $clone = $this->pop();
        $clone->shouldNotBe($this);
        $clone->shouldBeLike(new PlainArray(['item 1', 'item 2']));
    }

    function it_pops_value_of_the_end_and_assigns_to_variable()
    {
        $items = ['item 1', 'item 2', 'item 3'];
        $this->beConstructedWith($items);
        $twin = new PlainArray($items);

        $popped = $this->pop();
        $popped->shouldBeLike($twin->pop($item));

        if ($item !== 'item 3') {
            throw new FailureException('Popped value should be assigned to provided variable');
        }

        $this->count()->shouldReturn(3);
        $popped->count()->shouldReturn(2);
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

    function it_returns_first_and_last_item()
    {
        $this->beConstructedWith(['item 1', 'item 2', 'item 3']);

        $this->first()->shouldReturn('item 1');
        $this->last()->shouldReturn('item 3');
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

    function it_implements_ArrayAccess()
    {
        $items = ['item 1', 'item 2', 'item 3'];
        $this->beConstructedWith($items);

        $this->shouldImplement(\ArrayAccess::class);

        $this->offsetExists(0)->shouldReturn(true);
        $this->offsetGet(0)->shouldReturn('item 1');
        $this[0]->shouldBe('item 1');
    }

    function it_does_not_allow_mutations_trough_ArrayAccess()
    {
        $items = ['item 1', 'item 2', 'item 3'];
        $this->beConstructedWith($items);

        $this->shouldThrow(\BadMethodCallException::class)->during('offsetSet', [0, 'mutated 1']);
        $this->shouldThrow(\BadMethodCallException::class)->during('offsetUnset', [0]);
    }

    function it_can_be_converted_to_AssocValue()
    {
        $this->beConstructedWith(['item 0', 'item 1', 'item 2']);

        $this->toAssocValue()->shouldBeLike(Wrap::assocArray(['0' => 'item 0', '1' => 'item 1', '2' => 'item 2']));
    }

    function it_can_be_converted_to_StringsArray()
    {
        $this->beConstructedWith(['one', 'two', 'three']);

        $this->toStringsArray()->shouldBeLike(Wrap::stringsArray(['one', 'two', 'three']));
    }

    function it_can_tell_if_has_element_or_not()
    {
        $this->beConstructedWith(['one', '2', 'three']);

        $this->hasElement('one')->shouldReturn(true);
        $this->hasElement('2')->shouldReturn(true);
        $this->hasElement(2)->shouldReturn(false);
        $this->hasElement('five')->shouldReturn(false);
    }

    function it_throws_InvalidArgumentException_when_sort_comparator_returns_something_other_than_int()
    {
        $this->beConstructedWith(['1', '2', '3']);

        $this->shouldThrow(\InvalidArgumentException::class)->during('sort', [new InvalidComparator()]);
    }

    function it_throws_InvalidArgumentException_when_unique_comparator_returns_something_other_than_int()
    {
        $this->beConstructedWith(['1', '1', '3']);

        $this->shouldThrow(\InvalidArgumentException::class)->during('unique', [new InvalidComparator()]);
    }

    function it_throws_InvalidArgumentException_when_filter_returns_something_else_than_boolean()
    {
        $this->beConstructedWith(['1', '1', '3']);

        $invalidFilter = function(): int {
            return 1;
        };

        $this->shouldThrow(\InvalidArgumentException::class)->during('filter', [$invalidFilter]);
    }

    private function entityComparator(): \Closure
    {
        return function (DummyEntity $entityA, DummyEntity $entityB): int {
            return $entityA->id <=> $entityB->id;
        };
    }
}
