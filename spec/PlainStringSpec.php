<?php 

namespace spec\GW\Value;

use GW\Value\PlainString;
use PhpSpec\ObjectBehavior;

final class PlainStringSpec extends ObjectBehavior 
{
    function it_is_initializable()
    {
        $this->beConstructedWith('string');
        $this->shouldHaveType(PlainString::class);
    }
}
