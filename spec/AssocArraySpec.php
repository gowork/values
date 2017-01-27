<?php

namespace spec\GW\Value;

use GW\Value\AssocArray;
use GW\Value\PlainArray;
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

    function it_should_return_keys()
    {
        $keys = $this->keys();
        $keys->shouldNotBe($this);
        $keys->shouldBeLike(new PlainArray([
            'a', 'b', 'c'
        ]));
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
        $filtered->shouldBeLike(new AssocArray([
            'a' => 'alf',
            'c' => 'clifford',
        ]));
    }

    function it_should_return_number_of_elements()
    {
        $this->count()->shouldBe(3);
    }

    function it_return_array_in_reversed_order()
    {
        $reversed = $this->reverse();
        $reversed->shouldNotBe($this);
        $reversed->shouldBeLike(new AssocArray([
            'c' => 'clifford',
            'b' => 'berni',
            'a' => 'alf',
        ]));
    }

    function it_return_shuffled_array()
    {
        $shuffled = $this->shuffle();
        $shuffled->shouldNotBe($this);
        $shuffled->shouldNotBeLike(new AssocArray([
            'a' => 'alf',
            'b' => 'berni',
            'c' => 'clifford',
        ]));
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
        $merged->shouldBeLike(new AssocArray([
            'a' => 'alf',
            'b' => 'berni',
            'c' => 'clifford',
            'd' => 'dummy'
        ]));
    }

    function it_can_delete_element_by_key()
    {
        $array = $this->without('a');
        $array->shouldNotBe($this);
        $array->shouldBeLike(new AssocArray([
            'b' => 'berni',
            'c' => 'clifford',
        ]));
    }

    function it_can_delete_element_by_value()
    {
        $array = $this->withoutElement('alf');
        $array->shouldNotBe($this);
        $array->shouldBeLike(new AssocArray([
            'b' => 'berni',
            'c' => 'clifford',
        ]));
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
}
