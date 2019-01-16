<?php

namespace spec\GW\Value;

use GW\Value\FixedNumber;
use GW\Value\Wrap;
use PhpSpec\ObjectBehavior;

final class FixedNumberSpec extends ObjectBehavior
{
    function it_can_be_created_from_int()
    {
        $this->beConstructedThrough('fromInt', [997, 0]);
        $this->toString()->shouldReturn('997');
        $this->scale()->shouldReturn(0);
    }

    function it_can_be_created_from_int_with_specified_scale()
    {
        $this->beConstructedThrough('fromInt', [997, 3]);
        $this->toString()->shouldReturn('997.000');
        $this->scale()->shouldReturn(3);
    }

    function it_can_be_created_from_float_with_specified_scale_resulting_rounded_value()
    {
        $this->beConstructedThrough('fromFloat', [1.55555, 3]);
        $this->toFloat()->shouldBe(1.556);
        $this->toString()->shouldReturn('1.556');
    }

    function it_can_be_created_from_float_with_fixed_scale()
    {
        $this->beConstructedThrough('fromFloat', [1.0, 5]);
        $this->toString()->shouldReturn('1.00000');
    }

    function it_can_be_created_from_tiny_float()
    {
        $this->beConstructedThrough('fromFloat', [0.00000000000000000000000000000001, 32]);
        $this->toString()->shouldReturn('0.00000000000000000000000000000001');
    }

    function it_can_be_created_from_string_with_guessed_scale()
    {
        $this->beConstructedFromString('123.456789');
        $this->toString()->shouldReturn('123.456789');
        $this->scale()->shouldReturn(6);
    }

    function it_can_be_created_from_string_with_specified_scale()
    {
        $this->beConstructedFromString('123.456789', 3);
        $this->toString()->shouldReturn('123.457');
        $this->scale()->shouldReturn(3);
    }

    function it_can_be_created_from_space_formatted_string()
    {
        $this->beConstructedFromString('12 000.12');
        $this->toString()->shouldReturn('12000.12');
    }

    function it_can_be_created_from_comma_formatted_string()
    {
        $this->beConstructedFromString('11,000.11');
        $this->toString()->shouldReturn('11000.11');
    }

    function it_can_be_scaled_up()
    {
        $this->beConstructedFromString('123.456');

        $scaled = $this->withScale(6);
        $scaled->shouldNotBe($this);
        $scaled->toString()->shouldReturn('123.456000');
        $scaled->scale()->shouldReturn(6);
    }

    function it_can_be_scaled_down()
    {
        $this->beConstructedFromString('123.456789');

        $scaled = $this->withScale(3);
        $scaled->shouldNotBe($this);
        $scaled->toString()->shouldReturn('123.457');
        $scaled->scale()->shouldReturn(3);
    }

    function it_can_be_scaled_down_to_integer()
    {
        $this->beConstructedFromString('123.5001');

        $scaled = $this->withScale(0);
        $scaled->toString()->shouldReturn('124');
        $scaled->scale()->shouldReturn(0);
    }

    function it_can_have_negative_scale_which_gives_integer_rounded_to_order_of_magnitude()
    {
        $this->beConstructedFromString('12345.666', -2);

        $this->toInt()->shouldReturn(12300);
        $this->toString()->shouldReturn('12300');
        $this->add(FixedNumber::fromInt(811))->toString()->shouldBe('13100');
        $this->divide(FixedNumber::fromInt(100))->toString()->shouldBe('100');
        $this->subtract(FixedNumber::fromInt(12400))->toString()->shouldBe('-100');
    }

    function it_returns_self_when_desired_scale_is_same_as_current()
    {
        $this->beConstructedFromString('123.456789');
        $this->withScale(6)->shouldReturn($this);
    }

    function it_can_be_casted_to_int()
    {
        $this->beConstructedFromString('123');
        $this->toInt()->shouldReturn(123);
    }

    function it_is_not_rounded_when_casted_to_int()
    {
        $this->beConstructedFromString('123.99');
        $this->toInt()->shouldReturn(123);
    }

    function it_can_be_casted_to_float_from_integer_input()
    {
        $this->beConstructedFromString('123');
        $this->toFloat()->shouldReturn(123.0);
    }

    function it_can_be_casted_to_float_from_decimal_input()
    {
        $this->beConstructedFromString('123.111');
        $this->toFloat()->shouldReturn(123.111);
    }

    function it_can_be_wrapped_with_StringValue()
    {
        $this->beConstructedFromString('123.456');
        $this->toStringValue()->shouldBeLike(Wrap::string('123.456'));
    }

    function it_can_be_formatted_as_StringValue()
    {
        $this->beConstructedFromString('123456.789');
        $this->format(3, ',', ' ')->toString()->shouldBe('123 456,789');
    }

    function it_can_be_compared_with_other_numbers()
    {
        $this->beConstructedFromString('100.005');

        $this->compare(Wrap::fixedNumber('100.004'))->shouldReturn(1);
        $this->compare(Wrap::fixedNumber('100.0049999999999999999999999999999'))->shouldReturn(1);

        $this->compare(Wrap::fixedNumber('100.005'))->shouldReturn(0);
        $this->compare(Wrap::fixedNumber('100.0050'))->shouldReturn(0);

        $this->compare(Wrap::fixedNumber('100.006'))->shouldReturn(-1);
        $this->compare(Wrap::fixedNumber('100.0055'))->shouldReturn(-1);
    }

    function it_is_grater_when_its_number_is_greater_in_left_number_scale()
    {
        $this->beConstructedFromString('100.005');
        $this->greaterThan($this)->shouldReturn(false);

        $this->greaterThan(Wrap::fixedNumber('100.004'))->shouldReturn(true);
        $this->greaterThan(Wrap::fixedNumber('100.005'))->shouldReturn(false);
        $this->greaterThan(Wrap::fixedNumber('100.006'))->shouldReturn(false);

        $this->greaterThan(Wrap::fixedNumber('100.0045'))->shouldReturn(false);
        $this->greaterThan(Wrap::fixedNumber('100.0051'))->shouldReturn(false);
        $this->greaterThan(Wrap::fixedNumber('100.0044'))->shouldReturn(true);
    }

    function it_is_lesser_when_its_number_is_lesser_in_left_number_scale()
    {
        $this->beConstructedFromString('100.0050');

        $this->lesserThan($this)->shouldReturn(false);
        $this->lesserThan(Wrap::fixedNumber('100.0051'))->shouldReturn(true);
        $this->lesserThan(Wrap::fixedNumber('100.0050'))->shouldReturn(false);
        $this->lesserThan(Wrap::fixedNumber('100.0049'))->shouldReturn(false);

        $this->lesserThan(Wrap::fixedNumber('100.00504'))->shouldReturn(false);
        $this->lesserThan(Wrap::fixedNumber('100.00505'))->shouldReturn(true);
    }

    function it_can_add_integer_number()
    {
        $this->beConstructedFromString('100.0001');

        $sum = $this->add(Wrap::fixedNumber(1));
        $sum->shouldNotBe($this);
        $sum->shouldBeLike(Wrap::fixedNumber('101.0001'));
    }

    function it_can_add_float_number()
    {
        $this->beConstructedFromString('100.0001');

        $sum = $this->add(Wrap::fixedNumber('0.0001'));
        $sum->shouldNotBe($this);
        $sum->shouldBeLike(Wrap::fixedNumber('100.0002'));
    }

    function it_can_subtract_integer_number()
    {
        $this->beConstructedFromString('100.0001');

        $sum = $this->subtract(Wrap::fixedNumber(1));
        $sum->shouldNotBe($this);
        $sum->shouldBeLike(Wrap::fixedNumber('99.0001'));
    }

    function it_can_subtract_float_number()
    {
        $this->beConstructedFromString('100.0001');

        $sum = $this->subtract(Wrap::fixedNumber('0.0001'));
        $sum->shouldNotBe($this);
        $sum->shouldBeLike(Wrap::fixedNumber('100.0000'));
    }

    function it_can_multiply_by_integer_number()
    {
        $this->beConstructedFromString('100.0');

        $result = $this->multiply(Wrap::fixedNumber(2));
        $result->shouldNotBe($this);
        $result->toString()->shouldBe('200.0');
    }

    function it_can_multiply_by_float_number_keeping_the_scale()
    {
        $this->beConstructedFromString('100.04');

        $sum = $this->multiply(Wrap::number(0.0150));
        $sum->shouldNotBe($this);

        // 100.04 * 0.02 = 2.00 (rounded from 2.0008)
        $sum->toString()->shouldBe('2.00');
    }

    function it_divides_numbers_keeping_left_number_scale()
    {
        $this->beConstructedFromString('1.9');

        $result = $this->divide(Wrap::fixedNumber('2.111'));
        $result->toString()->shouldReturn('0.9');
    }

    function it_can_divide_two_integers_and_by_default_result_is_integer_with_rounding()
    {
        $this->beConstructedFromString('10');

        $examples = [
            // divisor, expectation
            ['2', '5'],
            ['4', '3'],
            ['7', '1'],
        ];

        foreach ($examples as $example) {
            [$divisor, $expectation] = $example;

            $result = $this->divide(Wrap::fixedNumber($divisor));
            $result->shouldNotBe($this);
            $result->toString()->shouldReturn($expectation);
        }
    }

    function it_can_divide_two_numbers_with_given_scale()
    {
        $this->beConstructedFromString('10.00');

        $examples = [
            // divisor, expectation, scale
            ['2', '5.00'],
            ['4', '2.50'],
            ['7', '1.43'],
            ['0.15', '66.67'],
        ];

        foreach ($examples as $example) {
            [$divisor, $expectation] = $example;

            $result = $this->divide(Wrap::fixedNumber($divisor));
            $result->shouldNotBe($this);
            $result->toString()->shouldReturn($expectation);
        }
    }

    function it_returns_absolute_value_from_positive_number()
    {
        $this->beConstructedFromString('1.123');
        $this->abs()->shouldReturn($this);
    }

    function it_returns_absolute_value_from_zero()
    {
        $this->beConstructedFromString('0.0');
        $this->abs()->shouldReturn($this);
    }

    function it_returns_absolute_value_from_negative_number()
    {
        $this->beConstructedFromString('-1.123');
        $this->abs()->shouldBeLike(FixedNumber::fromString('1.123'));
    }

    function it_rounds_positive_number()
    {
        $this->beConstructedFromString('1.1234509');

        $examples = [
            // scale, expectation
            [0, '1.0000000'],
            [1, '1.1000000'],
            [4, '1.1235000'],
            [6, '1.1234510'],
            [8, '1.1234509'],
        ];

        foreach ($examples as $example) {
            [$scale, $expectation] = $example;

            $result = $this->round($scale);
            $result->toString()->shouldReturn($expectation);
        }
    }

    function it_rounds_negative_number()
    {
        $this->beConstructedFromString('-1.1234509');

        $examples = [
            // scale, expectation
            [0, '-1.0000000'],
            [1, '-1.1000000'],
            [4, '-1.1235000'],
            [6, '-1.1234510'],
            [8, '-1.1234509'],
        ];

        foreach ($examples as $example) {
            [$scale, $expectation] = $example;

            $result = $this->round($scale);
            $result->toString()->shouldReturn($expectation);
        }
    }

    function it_can_be_rounded_to_floor_with_given_precision_when_positive()
    {
        $this->beConstructedFromString('1.91919');

        $examples = [
            // scale, expectation
            [0, '1.00000'],
            [2, '1.91000'],
            [4, '1.91910'],
        ];

        foreach ($examples as $example) {
            [$scale, $expectation] = $example;

            $result = $this->floor($scale);
            $result->shouldNotBe($this);
            $result->toString()->shouldReturn($expectation);
        }
    }

    function it_can_be_rounded_to_floor_with_given_precision_when_negative()
    {
        $this->beConstructedFromString('-1.19111');

        $examples = [
            // scale, expectation
            [0, '-2.00000'],
            [2, '-1.20000'],
            [4, '-1.19120'],
        ];

        foreach ($examples as $example) {
            [$scale, $expectation] = $example;

            $result = $this->floor($scale);
            $result->shouldNotBe($this);
            $result->toString()->shouldReturn($expectation);
        }
    }

    function it_can_be_rounded_to_ceil_with_given_precision_when_positive()
    {
        $this->beConstructedFromString('1.91911');

        $examples = [
            // scale, expectation
            [0, '2.00000'],
            [2, '1.92000'],
            [4, '1.91920'],
        ];

        foreach ($examples as $example) {
            [$scale, $expectation] = $example;

            $result = $this->ceil($scale);
            $result->shouldNotBe($this);
            $result->toString()->shouldReturn($expectation);
        }
    }

    function it_can_be_rounded_to_ceil_with_given_precision_when_negative()
    {
        $this->beConstructedFromString('-1.19911');

        $examples = [
            // scale, expectation
            [0, '-1.00000'],
            [2, '-1.19000'],
            [4, '-1.19910'],
        ];

        foreach ($examples as $example) {
            [$scale, $expectation] = $example;

            $result = $this->ceil($scale);
            $result->shouldNotBe($this);
            $result->toString()->shouldReturn($expectation);
        }
    }

    function it_is_empty_when_is_zero()
    {
        $this->beConstructedFromString('0');
        $this->isEmpty()->shouldReturn(true);
    }

    function it_is_not_empty_when_is_not_zero()
    {
        $this->beConstructedFromString('0.00000001');
        $this->isEmpty()->shouldReturn(false);
    }

    function it_can_be_casted_to_string()
    {
        $this->beConstructedThrough('fromFloat', [0.12345, 5]);
        $this->__toString()->shouldReturn('0.12345');
    }

    private function beConstructedFromString(string $number, int $scale = null): void
    {
        $this->beConstructedThrough('fromString', [$number, $scale]);
    }
}
