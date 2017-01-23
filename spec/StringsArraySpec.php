<?php

namespace spec\GW\Value;

use GW\Value\Arrays;
use GW\Value\Strings;
use GW\Value\StringsArray;
use PhpSpec\ObjectBehavior;

final class StringsArraySpec extends ObjectBehavior
{
    function it_is_initializable_with_primitive_string()
    {
        $this->beConstructedWith(Arrays::create(['string']));

        $this->shouldHaveType(StringsArray::class);

        $this->count()->shouldReturn(1);
        $this->toString()->shouldReturn('string');
    }

    function it_is_initializable_with_string_value()
    {
        $this->beConstructedWith(Arrays::create([Strings::create('string')]));

        $this->shouldHaveType(StringsArray::class);

        $this->count()->shouldReturn(1);
        $this->toString()->shouldReturn('string');
    }

    function it_trims_all_contained_strings()
    {
        $this->beConstructedWith(Arrays::create(['  string1  ', '  string2  ']));

        $this->trim()->shouldBeLike(new StringsArray(Arrays::create(['string1', 'string2'])));
    }
}
