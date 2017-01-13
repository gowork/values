<?php

namespace spec\GW\Value;

use GW\Value\StringValue;
use PhpSpec\ObjectBehavior;

class StringValueSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(StringValue::class);
    }
}
