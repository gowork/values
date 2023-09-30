<?php

namespace spec\GW\Value;

use GW\Value\Filters;
use GW\Value\InfiniteIterableValue;
use GW\Value\PlainArray;
use GW\Value\Sorts;
use GW\Value\Wrap;
use PhpSpec\Exception\Example\FailureException;
use PhpSpec\ObjectBehavior;
use function range;

final class InfiniteIterableValueSpec extends ObjectBehavior
{
    function it_can_be_converted_to_array_value()
    {
        $items = ['item 1', 'item 2', 'item 3'];
        $this->beConstructedWith($items);
        $this->toArrayValue()->shouldBeLike(Wrap::array($items));
    }

    function it_can_be_converted_to_simple_assoc_array()
    {
        $items = ['item 1', 'item 2', 'item 3'];
        $this->beConstructedWith($items);
        $this->toAssocArray()->shouldBeLike([0 => 'item 1', 1 => 'item 2', 2 => 'item 3']);
    }

    function it_can_be_converted_to_keyed_assoc_array()
    {
        $items = ['foo' => 'item 1', 'bar' => 'item 2'];
        $this->beConstructedWith($items);
        $this->toAssocArray()->shouldBeLike(['foo' => 'item 1', 'bar' => 'item 2']);
    }

    function it_can_be_converted_to_keyed_assoc_array_with_map_filter()
    {
        $items = ['foo' => 'item 1', 'bar' => 'item 2'];
        $this->beConstructedWith($items);
        $this
            ->map(fn(string $value, string $key): string => "{$value} mod")
            ->filter(fn(string $value): bool => true)
            ->toAssocArray()
            ->shouldBeLike(['foo' => 'item 1 mod', 'bar' => 'item 2 mod']);
    }

    function it_returns_items()
    {
        $items = ['item 1', 'item 2', 'item 3'];
        $this->beConstructedWith($items);
        $this->toArray()->shouldReturn($items);
    }

    function it_returns_keys()
    {
        $items = ['item 1', 'item 2', 'item 3'];
        $this->beConstructedWith($items);
        $this->keys()->toArray()->shouldReturn([0, 1, 2]);
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
        $mapped = $this->map(fn($value): int => (int)$value);

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

    function it_joins_with_other_iterable()
    {
        $this->beConstructedWith(['a 1', 'a 2', 'a 3']);

        $joined = $this->join(['b 1', 'b 2', 'b 3']);

        $joined->shouldNotBe($this);
        $joined->toArray()->shouldBeLike(['a 1', 'a 2', 'a 3', 'b 1', 'b 2', 'b 3']);
    }

    function it_joins_with_other_IterableValue()
    {
        $this->beConstructedWith(['a 1', 'a 2', 'a 3']);

        $joined = $this->join(new InfiniteIterableValue(['b 1', 'b 2', 'b 3']));

        $joined->shouldNotBe($this);
        $joined->toArray()->shouldBeLike(['a 1', 'a 2', 'a 3', 'b 1', 'b 2', 'b 3']);
    }

    function it_slices_given_part()
    {
        $this->beConstructedWith(['item 1', 'item 2', 'item 3', 'item 4', 'item 5', 'item 6']);

        $this->slice(0, 1)->toArray()->shouldNotBe($this->toArray());
        $this->slice(0, 1)->toArray()->shouldBeLike(['item 1']);
        $this->slice(1, 4)->toArray()->shouldBeLike(['item 2', 'item 3', 'item 4', 'item 5']);
        $this->slice(5, 1)->toArray()->shouldBeLike(['item 6']);
    }

    function it_returns_empty_set_for_slice_with_zero_length()
    {
        $this->beConstructedWith(['item 1', 'item 2', 'item 3', 'item 4', 'item 5', 'item 6']);

        $this->slice(0, 0)->toArray()->shouldBeLike([]);
        $this->slice(1, 0)->toArray()->shouldBeLike([]);
        $this->slice(4, 0)->toArray()->shouldBeLike([]);
        $this->slice(55, 0)->toArray()->shouldBeLike([]);
    }

    function it_throws_when_used_on_negative_offset_or_length_in_slice()
    {
        $this->beConstructedWith(['item 1', 'item 2', 'item 3', 'item 4', 'item 5', 'item 6']);

        $this->shouldThrow()->during('slice', [-1, 10]);
        $this->shouldThrow()->during('slice', [-1, -110]);
        $this->shouldThrow()->during('slice', [1, -110]);
    }

    function it_skips_and_takes_given_part()
    {
        $this->beConstructedWith(['item 1', 'item 2', 'item 3', 'item 4', 'item 5', 'item 6']);

        $this->take(1)->toArray()->shouldNotBe($this->toArray());
        $this->take(1)->toArray()->shouldBeLike(['item 1']);
        $this->skip(1)->take(4)->toArray()->shouldBeLike(['item 2', 'item 3', 'item 4', 'item 5']);
        $this->skip(5)->take(1)->toArray()->shouldBeLike(['item 6']);
    }

    function it_slice_and_do_not_take_elements_above_end_index()
    {
        $takenElements = 0;
        $iterator = function () use (&$takenElements) {
            foreach (range(0, 123) as $item) {
                $takenElements++;
                yield $item;
            }
        };

        $this->beConstructedWith($iterator());
        $this->slice(0, 3)->toArray()->shouldEqual([0, 1, 2]);

        if ($takenElements !== 3) {
            throw new FailureException('Slice should not take elements above end index');
        }
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
        $this->beConstructedWith($items, true);

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

        $this->beConstructedWith([$joe, $william, $jack, $averell], true);

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
        $this->beConstructedWith($items, true);

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

        $this->beConstructedWith([$joe, $william, $jack, $averell], true);

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

    function it_can_be_used_after_iteration_break()
    {
        $this->beConstructedThrough(function () {
            $value = new InfiniteIterableValue([1, 2, 3, 4]);
            $value = $value->map(function (int $v): int {return $v+1;});

            foreach ($value as $k => $v) {
                if ($k === 1) {
                    break;
                }
            }

            return $value;
        });

        $this->toArray()->shouldEqual([2, 3, 4, 5]);
    }

    function it_can_be_casted_to_array_twice()
    {
        $this->beConstructedWith([1, 2, 3]);
        $this->toArray()->shouldEqual([1, 2, 3]);
        $this->toArray()->shouldEqual([1, 2, 3]);
    }

    function it_cannot_be_used_twice_if_generator()
    {
        $this->beConstructedWith((function () {yield from [2, 2, 5];})());
        $this->toArray()->shouldEqual([2, 2, 5]);
        $this->shouldThrow()->during('toArray');
    }

    function it_can_be_chained()
    {
        $this->beConstructedWith([1, 2, 3]);
        $modified = $this
            ->filter(function (int $i): bool {return $i === 2;})
            ->map(function (int $i): int {return $i*2;});

        $modified
            ->toArray()
            ->shouldEqual([4]);

        $modified
            ->map(function (int $i): int {return $i*2;})
            ->toArray()
            ->shouldEqual([8]);

        $modified
            ->toArray()
            ->shouldEqual([4]);

        $modified
            ->toArray()
            ->shouldEqual([4]);

        $modified
            ->toArray()
            ->shouldEqual([4]);

        $this
            ->toArray()
            ->shouldEqual([1, 2, 3]);
    }

    function it_allows_to_split_array_to_chunks_of_given_size()
    {
        $this->beConstructedWith([1, 2, 3, 4, 5, 6, 7, 8, 9]);

        $this->chunk(3)->toArray()->shouldBeLike([[1, 2, 3], [4, 5, 6], [7, 8, 9]]);
        $this->chunk(5)->toArray()->shouldBeLike([[1, 2, 3, 4, 5], [6, 7, 8, 9]]);
    }

    function it_allows_to_flatten_array_of_chunks()
    {
        $this->beConstructedWith([[1, 2, 3], [4, 5, 6], [7, 8, 9]]);
        $this->flatten()->toArray()->shouldBeLike([1, 2, 3, 4, 5, 6, 7, 8, 9]);
    }

    function it_allows_to_check_if_any_element_satisfies_filter_condition()
    {
        $this->beConstructedWith([2, 4, 6, 8, 10, 12, 14, 16]);

        $isEven = function (int $value): bool {
            return ($value % 2) === 0;
        };
        $isOdd = Filters::not($isEven);
        $isTwo = Filters::equal(2);
        $isHundred = Filters::equal(100);

        $this->any($isEven)->shouldReturn(true);
        $this->any($isTwo)->shouldReturn(true);

        $this->any($isOdd)->shouldReturn(false);
        $this->any($isHundred)->shouldReturn(false);
    }

    function it_allows_to_check_if_every_element_satisfies_filter_condition()
    {
        $this->beConstructedWith([2, 4, 6, 8, 10, 12, 14, 16]);

        $isEven = function (int $value): bool {
            return ($value % 2) === 0;
        };
        $isOdd = Filters::not($isEven);
        $isTwo = Filters::equal(2);
        $isHundred = Filters::equal(100);

        $this->every($isEven)->shouldReturn(true);

        $this->every($isTwo)->shouldReturn(false);
        $this->every($isOdd)->shouldReturn(false);
        $this->every($isHundred)->shouldReturn(false);
    }

    function it_returns_first_and_last_item()
    {
        $this->beConstructedWith(['item 1', 'item 2', 'item 3']);

        $this->first()->shouldReturn('item 1');
        $this->last()->shouldReturn('item 3');
    }

    function it_finds_last_item_that_matches_condition()
    {
        $this->beConstructedWith(['item 1', 'item 2', 'item 3', 'item 11', 'item 22', 'item 33']);

        $this
            ->findLast(function (string $item): bool {
                return strpos($item, '2') !== false;
            })
            ->shouldReturn('item 22');
    }

    function it_finds_null_when_no_item_matches_condition()
    {
        $this->beConstructedWith(['item 1', 'item 2', 'item 3']);

        $this
            ->find(function (string $item): bool {
                return strpos($item, 'x') !== false;
            })
            ->shouldReturn(null);

        $this
            ->findLast(function (string $item): bool {
                return strpos($item, 'x') !== false;
            })
            ->shouldReturn(null);
    }

    function it_handles_repeated_keys_properly_with_toArray()
    {
        $iterator = function () {
            foreach (range(0, 5) as $item) {
                yield 0 => $item;
            }
        };

        $this->beConstructedWith($iterator());
        $this->toArray()->shouldEqual([0, 1, 2, 3, 4, 5]);
    }

    function it_handles_repeated_keys_properly_with_toAssocArray()
    {
        $iterator = function () {
            foreach (range(0, 5) as $item) {
                yield 0 => $item;
            }
        };

        $this->beConstructedWith($iterator());
        $this->toAssocArray()->shouldEqual([0 => 5]);
    }

    function it_handles_repeated_keys_properly_with_keys()
    {
        $iterator = function () {
            foreach (range(0, 5) as $item) {
                yield 0 => $item;
            }
        };

        $this->beConstructedWith($iterator());
        $this->keys()->toArray()->shouldEqual([0, 0, 0, 0, 0, 0]);
    }

    function it_handles_repeated_keys_properly_with_map()
    {
        $iterator = function () {
            foreach (range(0, 5) as $item) {
                yield 0 => $item;
            }
        };

        $this->beConstructedWith($iterator());
        $this->map(fn(int $value, int $key): int => $key)->toArray()->shouldEqual([0, 0, 0, 0, 0, 0]);
    }

    function it_handles_repeated_keys_properly_with_slice()
    {
        $iterator = function () {
            foreach (range(0, 5) as $item) {
                yield 0 => $item;
            }
        };

        $this->beConstructedWith($iterator());
        $this->slice(1, 2)->toArray()->shouldEqual([1, 2]);
    }

    function it_handles_numeric_strings_key_as_int_from_array()
    {
        $this->beConstructedWith(['0' => 'zero', '1' => 'one']);
        $this->map(fn(string $val, int $key): string => $val)
            ->keys()->toArray()->shouldEqual([0, 1]);
    }

    function it_handles_numeric_strings_key_as_string_from_iterator()
    {
        $pairs = [['0', 'zero'], ['1', 'one'], ['1', 'one one']];

        $iterator = function () use ($pairs) {
            foreach ($pairs as [$key, $item]) {
                yield $key => $item;
            }
        };

        $this->beConstructedWith($iterator());
        $this->map(fn(string $val, string $key): string => $val)
            ->keys()->toArray()->shouldEqual(['0', '1', '1']);
    }

    function it_handles_object_keys()
    {
        $pairs = [[(object)['foo' => 'bar'], 'zero'], [(object)['foo' => 'baz'], 'one one']];

        $iterator = function () use ($pairs) {
            foreach ($pairs as [$key, $item]) {
                yield $key => $item;
            }
        };

        $this->beConstructedWith($iterator());
        $this->map(fn(string $val, object $key): string => $val)
            ->keys()->toArray()->shouldBeLike([(object)['foo' => 'bar'], (object)['foo' => 'baz']]);
    }

    function it_can_be_flipped()
    {
        $this->beConstructedWith(['foo' => 'zero', 'bar' => 'one']);
        $this->flip()
            ->keys()->toArray()->shouldEqual(['zero', 'one']);
        $this->flip()
            ->values()->toArray()->shouldEqual(['foo', 'bar']);
    }

    function it_can_be_flipped_numeric_keys()
    {
        $pairs = [['0', 'zero'], ['1', 'one']];

        $iterator = function () use ($pairs) {
            foreach ($pairs as [$key, $item]) {
                yield $key => $item;
            }
        };

        $this->beConstructedWith($iterator());
        $this->flip()
            ->values()->toArray()->shouldEqual(['0', '1']);
    }

    private function entityComparator(): \Closure
    {
        return function (DummyEntity $entityA, DummyEntity $entityB): int {
            return $entityA->id <=> $entityB->id;
        };
    }
}
