<?php 

namespace spec\GW\Value;

use GW\Value\ArrayValue;
use GW\Value\AssocValue;
use GW\Value\FixedNumber;
use GW\Value\FloatNumber;
use GW\Value\IntegerNumber;
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

    function it_wraps_integer_number()
    {
        $this->beConstructedThrough('number', [111]);
        $this->shouldHaveType(IntegerNumber::class);
    }

    function it_wraps_strict_integer_number()
    {
        $this->beConstructedThrough('int', [111]);
        $this->shouldHaveType(IntegerNumber::class);
    }

    function it_wraps_floating_number()
    {
        $this->beConstructedThrough('number', [111.11]);
        $this->shouldHaveType(FloatNumber::class);
    }

    function it_wraps_floating_point_integer_value_with_IntegerNumber()
    {
        $this->beConstructedThrough('number', [111.00]);
        $this->shouldHaveType(IntegerNumber::class);
    }

    function it_wraps_strict_floating_number()
    {
        $this->beConstructedThrough('float', [111.00]);
        $this->shouldHaveType(FloatNumber::class);
    }

    function it_wraps_fixed_number_from_int()
    {
        $this->beConstructedThrough('fixedNumber', [111, 2]);
        $this->shouldHaveType(FixedNumber::class);
    }

    function it_wraps_fixed_number_from_float()
    {
        $this->beConstructedThrough('fixedNumber', [111.222, 2]);
        $this->shouldHaveType(FixedNumber::class);
    }

    function it_wraps_fixed_number_from_NumberValue()
    {
        $this->beConstructedThrough('fixedNumber', [new IntegerNumber(111), 2]);
        $this->shouldHaveType(FixedNumber::class);
    }
}
