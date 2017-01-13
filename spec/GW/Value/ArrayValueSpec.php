<?php

namespace spec\GW\Value;

use GW\Value\ArrayValue;
use PhpSpec\ObjectBehavior;

class ArrayValueSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(ArrayValue::class);
    }
}
