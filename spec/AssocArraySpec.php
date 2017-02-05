<?php

namespace spec\GW\Value;

use GW\Value\Arrays;
use GW\Value\AssocArray;
use PhpSpec\ObjectBehavior;

final class AssocArraySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->beConstructedWith([]);
        $this->shouldHaveType(AssocArray::class);
    }

    function it_transforms_all_keys_to_strings()
    {
        $this->beConstructedWith(['zero', 'one', 'two']);

        $this->keys()->shouldBeLike(Arrays::strings(['0', '1', '2']));
    }

    function it_can_be_created_from_array()
    {
        $this->beConstructedThrough('fromArray', [['a' => 'alf', 'b' => 'bernie']]);
    }
}
