<?php

namespace spec\GW\Value;

use PhpSpec\ObjectBehavior;

final class SortsSpec extends ObjectBehavior
{
    function it_should_return_callable_when_initialized_through_asc()
    {
        $this->beConstructedThrough('asc', []);
        $this(1, 2)->shouldReturn(-1);
        $this(2, 2)->shouldReturn(0);
        $this(2, 1)->shouldReturn(1);
    }

    function it_should_return_callable_when_initialized_through_desc()
    {
        $this->beConstructedThrough('desc', []);
        $this(1, 2)->shouldReturn(1);
        $this(2, 2)->shouldReturn(0);
        $this(2, 1)->shouldReturn(-1);
    }
}
