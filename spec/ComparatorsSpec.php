<?php

namespace spec\GW\Value;

use GW\Value\FixedNumber;
use PhpSpec\ObjectBehavior;

final class ComparatorsSpec extends ObjectBehavior
{
    function it_has_number_comparator()
    {
        $this->beConstructedThrough('numbers');
        $this(FixedNumber::from('1.1'), FixedNumber::from('1.2'))->shouldReturn(-1);
        $this(FixedNumber::from('1.2'), FixedNumber::from('1.2'))->shouldReturn(0);
        $this(FixedNumber::from('1.3'), FixedNumber::from('1.2'))->shouldReturn(1);
    }

    function it_has_reversed_number_comparator()
    {
        $this->beConstructedThrough('reversedNumbers');
        $this(FixedNumber::from('1.1'), FixedNumber::from('1.2'))->shouldReturn(1);
        $this(FixedNumber::from('1.2'), FixedNumber::from('1.2'))->shouldReturn(0);
        $this(FixedNumber::from('1.3'), FixedNumber::from('1.2'))->shouldReturn(-1);
    }
}
