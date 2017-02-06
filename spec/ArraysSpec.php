<?php 

namespace spec\GW\Value;

use PhpSpec\ObjectBehavior;
use GW\Value\ArrayValue;
use GW\Value\AssocArray;
use GW\Value\StringsArray;

final class ArraysSpec extends ObjectBehavior 
{
    function it_should_return_arrayvalue_instance_when_initialized_through_create()
    {
        $this->beConstructedThrough('create', [[]]);
        $this->shouldHaveType(ArrayValue::class);
    }

    function it_should_return_arrayvalue_instance_when_initialized_through_createfromvalues()
    {
        $this->beConstructedThrough('createFromValues', [[], [], []]);
        $this->shouldHaveType(ArrayValue::class);
    }

    function it_should_return_arrayassoc_instance_when_initialized_through_assoc()
    {
        $this->beConstructedThrough('assoc', [[]]);
        $this->shouldHaveType(AssocArray::class);
    }

    function it_should_return_stringvalue_when_initialized_through_strings()
    {
        $this->beConstructedThrough('strings', [['a', 'b', 'c']]);
        $this->shouldHaveType(StringsArray::class);
    }
}
