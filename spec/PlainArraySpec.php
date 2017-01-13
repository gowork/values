<?php 

namespace spec\GW\Value;

use GW\Value\PlainArray;
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
}
