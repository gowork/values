<?php

namespace spec\GW\Value;

use GW\Value\Wrap;
use GW\Value\AssocArray;
use PhpSpec\ObjectBehavior;

final class AssocArraySpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith([
            'a' => 'alf',
            'b' => 'berni',
            'c' => 'clifford',
        ]);
    }

    function it_is_initializable()
    {
        $this->beConstructedWith([]);
        $this->shouldHaveType(AssocArray::class);
    }

    function it_should_return_keys()
    {
        $keys = $this->keys();
        $keys->shouldNotBe($this);
        $keys->shouldBeLike(Wrap::stringsArray(['a', 'b', 'c']));
    }

    function it_should_return_filtered_array()
    {
        $this->beConstructedWith([
            'a' => 'alf',
            'b' => '',
            'c' => 'clifford',
        ]);

        $filtered = $this->filterEmpty();
        $filtered->shouldNotBe($this);
        $filtered->shouldBeLike(
            new AssocArray([
                'a' => 'alf',
                'c' => 'clifford',
            ])
        );
    }

    function it_should_return_number_of_elements()
    {
        $this->count()->shouldBe(3);
    }

    function it_return_array_in_reversed_order()
    {
        $reversed = $this->reverse();
        $reversed->shouldNotBe($this);
        $reversed->shouldBeLike(
            new AssocArray([
                'c' => 'clifford',
                'b' => 'berni',
                'a' => 'alf',
            ])
        );
    }

    function it_return_shuffled_array()
    {
        $shuffled = $this->shuffle();
        $shuffled->shouldNotBe($this);
        $shuffled->shouldNotBeLike(
            new AssocArray([
                'a' => 'alf',
                'b' => 'berni',
                'c' => 'clifford',
            ])
        );
    }

    function it_return_unique_values()
    {
        $this->beConstructedWith(['a', 'a', 'a']);
        $this->unique()->shouldBeLike(new AssocArray(['a']));
    }

    function it_can_merge_two_arrays()
    {
        $merged = $this->merge(new AssocArray(['d' => 'dummy']));
        $merged->shouldNotBe($this);
        $merged->shouldBeLike(
            new AssocArray([
                'a' => 'alf',
                'b' => 'berni',
                'c' => 'clifford',
                'd' => 'dummy'
            ])
        );
    }

    function it_can_delete_element_by_key()
    {
        $array = $this->without('a');
        $array->shouldNotBe($this);
        $array->shouldBeLike(
            new AssocArray([
                'b' => 'berni',
                'c' => 'clifford',
            ])
        );
    }

    function it_can_delete_element_by_value()
    {
        $array = $this->withoutElement('alf');
        $array->shouldNotBe($this);
        $array->shouldBeLike(
            new AssocArray([
                'b' => 'berni',
                'c' => 'clifford',
            ])
        );
    }

    function it_can_return_value_by_key()
    {
        $this->get('a')->shouldBe('alf');
        $this->get('z', 'zorro')->shouldBe('zorro');
    }

    function it_can_check_if_element_exists_by_key()
    {
        $this->has('a')->shouldBe(true);
        $this->has('z')->shouldBe(false);
    }

    function it_can_return_first_value()
    {
        $this->first()->shouldBe('alf');
    }

    function it_can_return_last_value()
    {
        $this->last()->shouldBe('clifford');
    }

    function it_can_return_array()
    {
        $this->toArray()->shouldBe(['alf', 'berni', 'clifford']);
    }

    function it_can_return_iterator()
    {
        $this->getIterator()->shouldBeAnInstanceOf(\Iterator::class);
    }

    function it_can_execute_callback_on_each_element(CallableMock $callableMock)
    {
        $callableMock->__invoke('alf', 'a')->shouldBeCalled();
        $callableMock->__invoke('berni', 'b')->shouldBeCalled();
        $callableMock->__invoke('clifford', 'c')->shouldBeCalled();

        $this->each($callableMock);
    }

    function it_transforms_all_keys_to_strings()
    {
        $this->beConstructedWith(['zero', 'one', 'two']);

        $this->keys()->shouldBeLike(Wrap::stringsArray(['0', '1', '2']));
    }

    function it_can_be_created_from_array()
    {
        $this->beConstructedThrough('fromArray', [['a' => 'alf', 'b' => 'bernie']]);
    }

    function it_can_be_map_values_with_transformer_callback()
    {
        $mapped = $this->map(function (string $value, string $key): string {
            return "{$key}: {$value}";
        });

        $mapped->shouldNotBe($this);
        $mapped->shouldBeLike(
            new AssocArray([
                'a' => 'a: alf',
                'b' => 'b: berni',
                'c' => 'c: clifford',
            ])
        );
    }

    function it_can_map_keys_with_transformer_callback()
    {
        $mapped = $this->mapKeys(function (string $key, string $value): string {
            return "{$key}: {$value}";
        });

        $mapped->shouldNotBe($this);
        $mapped->shouldBeLike(
            new AssocArray([
                'a: alf' => 'alf',
                'b: berni' => 'berni',
                'c: clifford' => 'clifford',
            ])
        );
    }

    function it_can_filter_with_callback_filter()
    {
        $filtered = $this->filter(function (string $value): bool {
            return $value === 'alf';
        });

        $filtered->shouldNotBe($this);
        $filtered->shouldBeLike(new AssocArray(['a' => 'alf']));
    }

    function it_can_be_sorted_with_comparator()
    {
        $reversed = $this->sort(function(string $a, string $b): int {
            return $b <=> $a;
        });

        $reversed->shouldNotBe($this);
        $reversed->shouldBeLike(
            new AssocArray([
                'c' => 'clifford',
                'b' => 'berni',
                'a' => 'alf',
            ])
        );
    }

    function it_can_be_sorted_by_key_with_comparator()
    {
        $reversed = $this->sortKeys(function(string $a, string $b): int {
            return $b <=> $a;
        });

        $reversed->shouldNotBe($this);
        $reversed->shouldBeLike(
            new AssocArray([
                'c' => 'clifford',
                'b' => 'berni',
                'a' => 'alf',
            ])
        );
    }

    function it_can_reduce_items_with_callback()
    {
        $this->reduce(
            function(string $reduced, string $value, string $key): string {
                return $reduced . "{$key}: {$value} ";
            },
            ''
        )->shouldReturn('a: alf b: berni c: clifford ');
    }

    function it_can_tell_if_is_not_empty()
    {
        $this->isEmpty()->shouldReturn(false);
    }

    function it_can_tell_if_is_empty()
    {
        $this->beConstructedWith([]);

        $this->isEmpty()->shouldReturn(true);
    }
}
