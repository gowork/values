<?php

namespace spec\GW\Value;

use PhpSpec\Exception\Example\FailureException;
use PhpSpec\ObjectBehavior;

final class SortsSpec extends ObjectBehavior
{
    function it_should_return_callable_when_initialized_through_asc()
    {
        $this->beConstructedThrough('asc', []);
        $this->shouldBeCallable();
    }

    function it_should_return_callable_when_initialized_through_desc()
    {
        $this->beConstructedThrough('desc', []);
        $this->shouldBeCallable();
    }
}
