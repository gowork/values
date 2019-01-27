<?php

namespace spec\GW\Value;

use GW\Value\FixedNumber;
use GW\Value\FloatNumber;
use GW\Value\IntegerNumber;
use GW\Value\Wrap;
use PhpSpec\ObjectBehavior;

final class FloatNumberSpec extends ObjectBehavior
{
    function it_can_guess_scale_of_integer_as_float()
    {
        $this->beConstructedWith(1.0);
        $this->scale()->shouldBe(0);
    }

    function it_can_guess_of_fraction_float()
    {
        $this->beConstructedWith(1.123);
        $this->scale()->shouldBe(3);
    }

    function it_can_guess_of_really_small_float_number()
    {
        $this->beConstructedWith(0.000000000000000055511151231257827021181583404541015625);
        $this->scale()->shouldBe(54);
    }

    function it_can_be_casted_to_int()
    {
        $this->beConstructedWith(1.9);
        $this->toInt()->shouldBe(1);
    }

    function it_return_scalar_float()
    {
        $this->beConstructedWith(1.0);
        $this->toFloat()->shouldBe(1.0);
    }

    function it_casts_to_string_without_scientific_notation()
    {
        $this->beConstructedWith(0.1+0.2-0.3);
        $this->toString()->shouldBe('0.000000000000000055511151231257827021181583404541015625');
    }

    function it_can_be_converted_to_string_value()
    {
        $this->beConstructedWith(0.1+0.2-0.3);
        $this->toStringValue()->shouldBeLike(Wrap::string('0.000000000000000055511151231257827021181583404541015625'));
    }

    function it_can_be_formatted_as_string()
    {
        $this->beConstructedWith(123456.789);
        $this->format(1, ',', ' ')->shouldBeLike(Wrap::string('123 456,8'));
    }

    function it_can_be_compared_with_other_numbers()
    {
        $this->beConstructedWith(2.3);

        $this->compare(new FloatNumber(2.2))->shouldBe(1);
        $this->greaterThan(new FloatNumber(2.2))->shouldBe(true);
        $this->equals(new FloatNumber(2.2))->shouldBe(false);

        $this->compare(new FloatNumber(2.3))->shouldBe(0);
        $this->greaterThan(new FloatNumber(2.3))->shouldBe(false);
        $this->lesserThan(new FloatNumber(2.3))->shouldBe(false);

        $this->compare(new FloatNumber(2.4))->shouldBe(-1);
        $this->greaterThan(new FloatNumber(2.4))->shouldBe(false);
        $this->lesserThan(new FloatNumber(2.4))->shouldBe(true);

        $this->compare(new IntegerNumber(2))->shouldBe(1);
        $this->compare(new IntegerNumber(3))->shouldBe(-1);

        $this->compare(FixedNumber::fromString('2.2'))->shouldBe(1);
        $this->compare(FixedNumber::fromString('2.3'))->shouldBe(0);
        $this->compare(FixedNumber::fromString('2.4'))->shouldBe(-1);
    }

    function it_can_add_other_number()
    {
        $this->beConstructedWith(0.5);

        $this->add(new FloatNumber(0.5))->shouldBeLike(new FloatNumber(1.0));
        $this->add(new FloatNumber(-0.5))->shouldBeLike(new FloatNumber(0.0));
    }

    function it_returns_self_when_added_zero()
    {
        $this->beConstructedWith(0.5);
        $this->add(FloatNumber::zero())->shouldBe($this);
    }

    function it_can_subtract_other_number()
    {
        $this->beConstructedWith(0.5);

        $this->subtract(new FloatNumber(0.5))->shouldBeLike(new FloatNumber(0.0));
        $this->subtract(new FloatNumber(-0.5))->shouldBeLike(new FloatNumber(1.0));
    }

    function it_returns_self_when_subtracted_zero()
    {
        $this->beConstructedWith(0.5);
        $this->subtract(FloatNumber::zero())->shouldBe($this);
    }

    function it_can_be_multiplied_by_other_number()
    {
        $this->beConstructedWith(0.5);

        $this->multiply(new FloatNumber(0.5))->shouldBeLike(new FloatNumber(0.25));
        $this->multiply(new FloatNumber(-0.5))->shouldBeLike(new FloatNumber(-0.25));
    }

    function it_returns_self_when_multiplied_by_one()
    {
        $this->beConstructedWith(0.5);
        $this->multiply(new FloatNumber(1.0))->shouldBe($this);
    }

    function it_can_be_divided_by_other_number()
    {
        $this->beConstructedWith(0.5);

        $this->divide(new FloatNumber(0.2))->shouldBeLike(new FloatNumber(2.5));
        $this->divide(new IntegerNumber(2))->shouldBeLike(new FloatNumber(0.25));
        $this->divide(new FloatNumber(-0.2))->shouldBeLike(new FloatNumber(-2.5));
        $this->divide(new IntegerNumber(-2))->shouldBeLike(new FloatNumber(-0.25));
    }

    function it_returns_self_when_divided_by_one()
    {
        $this->beConstructedWith(0.5);
        $this->divide(new FloatNumber(1.0))->shouldBe($this);
    }

    function it_throws_error_when_divided_by_zero()
    {
        $this->beConstructedWith(0.5);
        $this->shouldThrow(\DivisionByZeroError::class)->during('divide', [FloatNumber::zero()]);
    }

    function it_returns_absolute_number()
    {
        $this->beConstructedWith(-2.5);
        $this->abs()->shouldBeLike(new FloatNumber(2.5));
    }

    function it_returns_self_as_absolute_when_positive()
    {
        $this->beConstructedWith(2.5);
        $this->abs()->shouldBe($this);
    }

    function it_returns_self_as_absolute_when_zero()
    {
        $this->beConstructedWith(0.0);
        $this->abs()->shouldBe($this);
    }

    function it_rounds_value_to_scale()
    {
        $this->beConstructedWith(1.2345);

        $this->round(1)->shouldBeLike(new FloatNumber(1.2));
        $this->round(2)->shouldBeLike(new FloatNumber(1.23));
        $this->round(3)->shouldBeLike(new FloatNumber(1.235));
    }

    function it_rounds_value_to_scale_with_custom_mode()
    {
        $this->beConstructedWith(1.2345);

        $this->round(1, PHP_ROUND_HALF_DOWN)->shouldBeLike(new FloatNumber(1.2));
        $this->round(2, PHP_ROUND_HALF_DOWN)->shouldBeLike(new FloatNumber(1.23));
        $this->round(3, PHP_ROUND_HALF_DOWN)->shouldBeLike(new FloatNumber(1.234));
    }

    function it_returns_integer_number_when_rounded_to_zero_scale()
    {
        $this->beConstructedWith(1.5);
        $this->round(0)->shouldBeLike(new IntegerNumber(2));
    }

    function it_rounds_to_integer_by_default()
    {
        $this->beConstructedWith(1.5);
        $this->round()->shouldBeLike(new IntegerNumber(2));
    }

    function it_can_round_with_negative_scale()
    {
        $this->beConstructedWith(12345.6);
        $this->round(-1)->shouldBeLike(new IntegerNumber(12350));
        $this->round(-2)->shouldBeLike(new IntegerNumber(12300));
    }

    function it_rounds_to_floor_with_scale()
    {
        $this->beConstructedWith(1.4567);

        $this->floor(3)->shouldBeLike(new FloatNumber(1.456));
        $this->floor(2)->shouldBeLike(new FloatNumber(1.45));
        $this->floor(1)->shouldBeLike(new FloatNumber(1.4));
    }

    function it_returns_integer_when_floored_to_zero_scale()
    {
        $this->beConstructedWith(1.5);
        $this->floor(0)->shouldBeLike(new IntegerNumber(1));
    }

    function it_floors_to_integer_by_default()
    {
        $this->beConstructedWith(1.5);
        $this->floor()->shouldBeLike(new IntegerNumber(1));
    }

    function it_rounds_to_ceil_with_scale()
    {
        $this->beConstructedWith(1.4567);

        $this->ceil(3)->shouldBeLike(new FloatNumber(1.457));
        $this->ceil(2)->shouldBeLike(new FloatNumber(1.46));
        $this->ceil(1)->shouldBeLike(new FloatNumber(1.5));
    }

    function it_returns_integer_when_ceil_to_zero_scale()
    {
        $this->beConstructedWith(1.5);
        $this->ceil(0)->shouldBeLike(new IntegerNumber(2));
    }

    function it_ceil_to_integer_by_default()
    {
        $this->beConstructedWith(1.5);
        $this->ceil()->shouldBeLike(new IntegerNumber(2));
    }

    function it_is_empty_when_is_zero()
    {
        $this->beConstructedWith(0.0);
        $this->isEmpty()->shouldBe(true);
    }

    function it_is_not_empty_when_is_non_zero()
    {
        $this->beConstructedWith(0.0000000000000000000001);
        $this->isEmpty()->shouldBe(false);
    }
}
