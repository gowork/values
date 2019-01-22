<?php

namespace spec\GW\Value;

use GW\Value\FloatNumber;
use GW\Value\IntegerNumber;
use GW\Value\Wrap;
use PhpSpec\ObjectBehavior;

final class IntegerNumberSpec extends ObjectBehavior
{
    function it_can_be_zero()
    {
        $this->beConstructedThrough('zero');
        $this->toInt()->shouldBe(0);
        $this->toFloat()->shouldBe(0.0);
        $this->isEmpty()->shouldBe(true);
    }

    function it_has_zero_scale()
    {
        $this->beConstructedWith(1);
        $this->scale()->shouldBe(0);
    }

    function it_is_integer()
    {
        $this->beConstructedWith(1);
        $this->isInteger()->shouldBe(true);
        $this->isDecimal()->shouldBe(false);
        $this->toInt()->shouldBe(1);
        $this->toFloat()->shouldBe(1.0);
        $this->toString()->shouldBe('1');
        $this->toStringValue()->shouldBeLike(Wrap::string('1'));
    }

    function it_can_be_formatted()
    {
        $this->beConstructedWith(1234);
        $this->format(2, ',', ' ')->shouldBeLike(Wrap::string('1 234,00'));
    }

    function it_can_compare_itself_with_other_numbers()
    {
        $this->beConstructedWith(5);

        $this->compare(new IntegerNumber(5))->shouldBe(0);
        $this->compare(new IntegerNumber(4))->shouldBe(1);
        $this->compare(new IntegerNumber(6))->shouldBe(-1);

        $this->equals(new IntegerNumber(5))->shouldBe(true);
        $this->equals(new IntegerNumber(4))->shouldBe(false);
        $this->equals(new IntegerNumber(6))->shouldBe(false);

        $this->greaterThan(new IntegerNumber(5))->shouldBe(false);
        $this->greaterThan(new IntegerNumber(4))->shouldBe(true);
        $this->greaterThan(new IntegerNumber(6))->shouldBe(false);

        $this->lesserThan(new IntegerNumber(5))->shouldBe(false);
        $this->lesserThan(new IntegerNumber(4))->shouldBe(false);
        $this->lesserThan(new IntegerNumber(6))->shouldBe(true);

        $this->compare(new FloatNumber(5.0))->shouldBe(0);
        $this->compare(new FloatNumber(4.9))->shouldBe(1);
        $this->compare(new FloatNumber(5.1))->shouldBe(-1);

        $this->equals(new FloatNumber(5.0))->shouldBe(true);
        $this->equals(new FloatNumber(4.9))->shouldBe(false);
        $this->equals(new FloatNumber(5.1))->shouldBe(false);

        $this->greaterThan(new FloatNumber(5.0))->shouldBe(false);
        $this->greaterThan(new FloatNumber(4.9))->shouldBe(true);
        $this->greaterThan(new FloatNumber(5.1))->shouldBe(false);

        $this->lesserThan(new FloatNumber(5.0))->shouldBe(false);
        $this->lesserThan(new FloatNumber(4.9))->shouldBe(false);
        $this->lesserThan(new FloatNumber(5.1))->shouldBe(true);
    }

    function it_adds_integer_giving_integer_result()
    {
        $this->beConstructedWith(12);

        $this->add(new IntegerNumber(13))->shouldBeLike(new IntegerNumber(25));
        $this->add(new IntegerNumber(-13))->shouldBeLike(new IntegerNumber(-1));
    }

    function it_adds_float_giving_float_result()
    {
        $this->beConstructedWith(12);

        $this->add(new FloatNumber(13.5))->shouldBeLike(new FloatNumber(25.5));
        $this->add(new FloatNumber(-13.5))->shouldBeLike(new FloatNumber(-1.5));
    }

    function it_returns_self_when_added_zero()
    {
        $this->beConstructedWith(12);
        $this->add(IntegerNumber::zero())->shouldBe($this);
    }

    function it_subtracts_integer_giving_integer_result()
    {
        $this->beConstructedWith(12);

        $this->subtract(new IntegerNumber(11))->shouldBeLike(new IntegerNumber(1));
        $this->subtract(new IntegerNumber(-11))->shouldBeLike(new IntegerNumber(23));
    }

    function it_subtracts_float_giving_float_result()
    {
        $this->beConstructedWith(12);

        $this->subtract(new FloatNumber(11.5))->shouldBeLike(new FloatNumber(0.5));
        $this->subtract(new FloatNumber(-11.5))->shouldBeLike(new FloatNumber(23.5));
    }

    function it_returns_self_when_subtracted_zero()
    {
        $this->beConstructedWith(12);
        $this->subtract(IntegerNumber::zero())->shouldBe($this);
    }

    function it_multiplies_integer_giving_integer_result()
    {
        $this->beConstructedWith(12);

        $this->multiply(new IntegerNumber(4))->shouldBeLike(new IntegerNumber(48));
        $this->multiply(new IntegerNumber(-4))->shouldBeLike(new IntegerNumber(-48));
    }

    function it_can_multiply_float_giving_integer_result()
    {
        $this->beConstructedWith(12);

        $this->multiply(new FloatNumber(1.5))->shouldBeLike(new IntegerNumber(18));
        $this->multiply(new FloatNumber(-1.5))->shouldBeLike(new IntegerNumber(-18));
    }

    function it_can_multiply_float_giving_float_result()
    {
        $this->beConstructedWith(12);

        $this->multiply(new FloatNumber(1.2))->shouldBeLike(new FloatNumber(12 * 1.2));
        $this->multiply(new FloatNumber(-1.2))->shouldBeLike(new FloatNumber(12 * -1.2));
    }

    function it_returns_self_when_multiplied_by_one()
    {
        $this->beConstructedWith(12);
        $this->multiply(new IntegerNumber(1))->shouldBe($this);
    }

    function it_can_by_divided_by_integer_giving_integer_result()
    {
        $this->beConstructedWith(12);

        $this->divide(new IntegerNumber(4))->shouldBeLike(new IntegerNumber(3));
        $this->divide(new IntegerNumber(-4))->shouldBeLike(new IntegerNumber(-3));
    }

    function it_can_be_divided_by_float_giving_integer_result()
    {
        $this->beConstructedWith(12);

        $this->divide(new FloatNumber(1.5))->shouldBeLike(new IntegerNumber(8));
        $this->divide(new FloatNumber(-1.5))->shouldBeLike(new IntegerNumber(-8));
    }

    function it_can_be_divided_by_float_giving_float_result()
    {
        $this->beConstructedWith(12);

        $this->divide(new FloatNumber(2.5))->shouldBeLike(new FloatNumber(12 / 2.5));
        $this->divide(new FloatNumber(-2.5))->shouldBeLike(new FloatNumber(12 / -2.5));
    }

    function it_returns_self_when_divided_by_one()
    {
        $this->beConstructedWith(12);
        $this->divide(new IntegerNumber(1))->shouldBe($this);
    }

    function it_calculates_absolute_value_from_negative_number()
    {
        $this->beConstructedWith(-10);
        $this->abs()->shouldBeLike(new IntegerNumber(10));
    }

    function it_returns_self_when_absolute_zero()
    {
        $this->beConstructedWith(0);
        $this->abs()->shouldReturn($this);
    }

    function it_returns_self_when_absolute_positive()
    {
        $this->beConstructedWith(10);
        $this->abs()->shouldReturn($this);
    }

    function it_returns_self_when_rounded()
    {
        $this->beConstructedWith(10);
        $this->abs()->shouldReturn($this);
    }

    function it_returns_self_with_default_round()
    {
        $this->beConstructedWith(123);
        $this->round()->shouldBe($this);
    }

    function it_returns_float_when_rounded_to_decimal_point()
    {
        $this->beConstructedWith(10);
        $this->round(2)->shouldBeLike(new FloatNumber(10.0));
    }

    function it_returns_integer_when_rounded_with_negative_scale()
    {
        $this->beConstructedWith(123456);
        $this->round(-2)->shouldBeLike(new IntegerNumber(123500));
        $this->round(-3)->shouldBeLike(new IntegerNumber(123000));
    }

    function it_returns_self_with_default_floor()
    {
        $this->beConstructedWith(123);
        $this->floor()->shouldBe($this);
    }

    function it_returns_float_when_floored_to_decimal_point()
    {
        $this->beConstructedWith(10);
        $this->floor(2)->shouldBeLike(new FloatNumber(10.0));
    }

    function it_returns_integer_when_floored_with_negative_scale()
    {
        $this->beConstructedWith(123456);
        $this->floor(-2)->shouldBeLike(new IntegerNumber(123400));
        $this->floor(-3)->shouldBeLike(new IntegerNumber(123000));
    }

    function it_returns_self_with_default_ceil()
    {
        $this->beConstructedWith(123);
        $this->ceil()->shouldBe($this);
    }

    function it_returns_float_when_ceiled_to_decimal_point()
    {
        $this->beConstructedWith(10);
        $this->ceil(2)->shouldBeLike(new FloatNumber(10.0));
    }

    function it_returns_integer_when_ceiled_with_negative_scale()
    {
        $this->beConstructedWith(123456);
        $this->ceil(-2)->shouldBeLike(new IntegerNumber(123500));
        $this->ceil(-3)->shouldBeLike(new IntegerNumber(124000));
    }
}
