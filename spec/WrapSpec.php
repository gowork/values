<?php 

namespace spec\GW\Value;

use GW\Value\ArrayValue;
use GW\Value\AssocValue;
use GW\Value\StringsArray;
use GW\Value\StringValue;
use PhpSpec\ObjectBehavior;

final class WrapSpec extends ObjectBehavior
{
    function it_should_return_ArrayValue_instance_when_initialized_through_array()
    {
        $this->beConstructedThrough('array', [[]]);
        $this->shouldHaveType(ArrayValue::class);
    }

    function it_should_return_ArrayValue_instance_when_initialized_through_createFromValues()
    {
        $this->beConstructedThrough('arrayFromValues', ['a', 'b', 'c']);
        $this->shouldHaveType(ArrayValue::class);
    }

    function it_should_return_AssocArray_instance_when_initialized_through_assocArray()
    {
        $this->beConstructedThrough('assocArray', [[]]);
        $this->shouldHaveType(AssocValue::class);
    }

    function it_should_return_StringValue_when_initialized_through_string()
    {
        $this->beConstructedThrough('string', ['string']);
        $this->shouldHaveType(StringValue::class);
    }

    function it_should_return_StringsArray_when_initialized_through_stringsArray()
    {
        $this->beConstructedThrough('stringsArray', [['a', 'b', 'c']]);
        $this->shouldHaveType(StringsArray::class);
    }
}
