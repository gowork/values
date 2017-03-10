<?php

namespace spec\GW\Value;

use PhpSpec\ObjectBehavior;

final class FloatNumberSpec extends ObjectBehavior
{
    function it_can_guess_scale()
    {
        $this->beConstructedWith(1.0);

        $this->scale()->shouldBe(0);
    }
}
