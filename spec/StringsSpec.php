<?php 

namespace spec\GW\Value;

use PhpSpec\ObjectBehavior;
use GW\Value\PlainString;

final class StringsSpec extends ObjectBehavior 
{
    function it_should_return_stringvalue_when_initialized_through_create()
    {
        $this->beConstructedThrough('create', ['string']);
        $this->shouldHaveType(PlainString::class);
    }
}
