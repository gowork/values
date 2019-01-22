<?php

namespace spec\GW\Value;

use GW\Value\Comparators;
use GW\Value\FixedNumber;
use GW\Value\FloatNumber;
use GW\Value\IntegerNumber;
use GW\Value\NumberValue;
use GW\Value\PlainNumbersArray;
use GW\Value\Wrap;
use PhpSpec\ObjectBehavior;

final class PlainNumbersArraySpec extends ObjectBehavior
{
    function it_returns_first_element()
    {
        $this->beConstructedWithNumbers(['1.1', '2.2', '3.3']);
        $this->first()->shouldBeLike(Wrap::number('1.1'));
    }

    function it_returns_last_element()
    {
        $this->beConstructedWithNumbers(['1.1', '2.2', '3.3']);
        $this->last()->shouldBeLike(Wrap::number('3.3'));
    }

    function it_can_tell_if_has_NumberValue_element()
    {
        $this->beConstructedWithNumbers(['1.1', '2.2', '3.3']);
        $this->hasElement(Wrap::number('2.2'))->shouldReturn(true);
    }

    function it_can_tell_if_has_number_element()
    {
        $this->beConstructedWithNumbers(['1.1', '2.2', '3.3']);
        $this->hasElement('2.2')->shouldReturn(true);
    }

    function it_can_tell_when_has_not_NumberValue_element()
    {
        $this->beConstructedWithNumbers(['1.1', '2.2', '3.3']);
        $this->hasElement(Wrap::fixedNumber('2.0'))->shouldReturn(false);
    }

    function it_can_tell_when_has_not_number_element()
    {
        $this->beConstructedWithNumbers(['1.1', '2.2', '3.3']);
        $this->hasElement('2.0')->shouldReturn(false);
    }

    function it_is_iterable()
    {
        $this->beConstructedWithNumbers([]);
        $this->shouldImplement(\IteratorAggregate::class);
        $this->getIterator()->shouldHaveType(\Iterator::class);
    }

    function it_has_count()
    {
        $this->beConstructedWithNumbers([1, 2]);
        $this->count()->shouldReturn(2);
    }

    function it_can_sum_numbers()
    {
        $this->beConstructedWithNumbers([1, 2, 3, 4, 5]);
        $this->sum()->toInt()->shouldBe(15);
    }

    function it_returns_integer_sum_when_all_numbers_are_integer()
    {
        $this->beConstructedWithNumbers([1, 2, 3,]);
        $this->sum()->shouldBeLike(new IntegerNumber(6));
    }

    function it_returns_float_sum_when_one_of_numbers_is_float_with_fraction()
    {
        $this->beConstructedWithNumbers([1, 2.1, 3,]);
        $this->sum()->shouldBeLike(new FloatNumber(6.1));
    }

    function it_returns_integer_sum_when_one_of_numbers_is_float_but_without_fraction()
    {
        $this->beConstructedWithNumbers([1, new FloatNumber(2.0), 3,]);
        $this->sum()->shouldBeLike(new IntegerNumber(6));
    }

    function it_returns_sum_as_fixed_point_number_when_first_number_is_fixed()
    {
        $this->beConstructedWithNumbers([
            FixedNumber::fromString('12.34'),
            new IntegerNumber(5),
            new FloatNumber(0.0111111)
        ]);

        $sum = $this->sum();
        $sum->shouldBeLike(FixedNumber::fromString('17.35'));
        $sum->scale()->shouldBe(2);
    }

    function it_calculates_zero_sum_from_empty_set()
    {
        $this->beConstructedWithNumbers([]);
        $this->sum()->shouldBeLike(IntegerNumber::zero());
    }

    function it_calculates_average_number()
    {
        $this->beConstructedWithNumbers([1, 2, 3, 4, 5]);
        $this->avg()->toInt()->shouldBe(3);
        $this->avg()->toFloat()->shouldBe(3.0);
    }

    function it_calculates_average_as_fixed_point_number_when_first_number_is_fixed()
    {
        $this->beConstructedWithNumbers([
            FixedNumber::fromString('12.34'),
            new IntegerNumber(5),
            new FloatNumber(0.0111111)
        ]);

        $avg = $this->avg();
        $avg->shouldBeLike(FixedNumber::fromString('5.78'));
        $avg->scale()->shouldBe(2);
    }

    function it_cannot_calculate_average_from_empty_set()
    {
        $this->beConstructedWithNumbers([]);
        $this->shouldThrow(\LogicException::class)->during('avg');
    }

    function it_returns_max_number()
    {
        $this->beConstructedWithNumbers([1, 10, 5, 3]);
        $this->max()->shouldBeLike(new IntegerNumber(10));
    }

    function it_returns_max_number_from_mixed_set_of_numbers()
    {
        $this->beConstructedWithNumbers([
            new IntegerNumber(-1000),
            new IntegerNumber(100),
            new FloatNumber(100.1),
            $max = FixedNumber::fromString('100.2'),
            FixedNumber::fromString('10.0'),
        ]);

        $this->max()->shouldBeLike($max);
    }

    function it_returns_min_number()
    {
        $this->beConstructedWithNumbers([1, 10, 5, 3]);
        $this->min()->shouldBeLike(new IntegerNumber(1));
    }

    function it_returns_min_number_from_mixed_set_of_numbers()
    {
        $this->beConstructedWithNumbers([
            new IntegerNumber(-100),
            new IntegerNumber(100),
            new FloatNumber(100.1),
            FixedNumber::fromString('100.2'),
            $min = FixedNumber::fromString('-100.1'),
        ]);

        $this->min()->shouldBeLike($min);
    }

    function it_can_run_function_on_each_element(CallableMock $function)
    {
        $numbers = [new IntegerNumber(100), new FloatNumber(100.1), FixedNumber::fromString('100.2')];
        $this->beConstructedWithNumbers($numbers);

        $this->each($function);

        $function->__invoke($numbers[0])->shouldHaveBeenCalled();
        $function->__invoke($numbers[1])->shouldHaveBeenCalled();
        $function->__invoke($numbers[2])->shouldHaveBeenCalled();
    }

    function it_can_filter_unique_numbers()
    {
        $this->beConstructedWithNumbers([
            $n100 = new IntegerNumber(100),
            new IntegerNumber(100),
            new FloatNumber(100.0),
            FixedNumber::fromString('100.0'),
            $n101 = new IntegerNumber(101),
            $n1011 = new FloatNumber(100.1),
            FixedNumber::fromString('100.1'),
        ]);

        $this->unique()->shouldBeLike(PlainNumbersArray::fromNumbers([$n100, $n101, $n1011]));
    }

    function it_can_filter_unique_numbers_using_custom_comparator()
    {
        $this->beConstructedWithNumbers([
            $n100 = new IntegerNumber(100),
            new IntegerNumber(100),
            new FloatNumber(100.0),
            FixedNumber::fromString('100.0'),
            $n101 = new IntegerNumber(101),
            new FloatNumber(100.1),
            FixedNumber::fromString('100.1'),
        ]);

        $this
            ->unique(function (NumberValue $a, NumberValue $b): int {
                return $a->round()->compare($b->round());
            })
            ->shouldBeLike(PlainNumbersArray::fromNumbers([$n100, $n101]));
    }

    function it_returns_array_of_number_values()
    {
        $numbers = [new IntegerNumber(1), new FloatNumber(1.1), FixedNumber::fromString('1.2')];
        $this->beConstructedWithNumbers($numbers);
        $this->toArray()->shouldReturn($numbers);
    }

    function it_can_filter_number_by_function()
    {
        $this->beConstructedWithNumbers([
            new IntegerNumber(-1),
            $n1 = new IntegerNumber(1),
            $n2 = new FloatNumber(0.001),
            new FloatNumber(-0.0001),
            FixedNumber::fromString('-1.123'),
            $n3 = FixedNumber::fromString('1.123'),
            $n4 = new IntegerNumber(1000),
        ]);

        $this
            ->filter(function (NumberValue $number): bool {
                return $number->greaterThan(IntegerNumber::zero());
            })
            ->shouldBeLike(PlainNumbersArray::fromNumbers([$n1, $n2, $n3, $n4]));
    }

    function it_can_filter_empty_numbers_meaning_zero()
    {
        $this->beConstructedWithNumbers([
            $one = new IntegerNumber(1),
            IntegerNumber::zero(),
            FloatNumber::zero(),
            $two = new FloatNumber(2.0),
            FixedNumber::fromString('0.0'),
            $three = FixedNumber::fromString('3.000'),
        ]);

        $this->filterEmpty()->shouldBeLike(PlainNumbersArray::fromNumbers([$one, $two, $three]));
    }

    function it_maps_numbers()
    {
        $this->beConstructedWithNumbers([new IntegerNumber(1), new FloatNumber(1.002), new FloatNumber(1.235)]);

        $fixedNumbers = $this
            ->map(function (NumberValue $number): FixedNumber {
                return FixedNumber::fromString($number->toString(), 2);
            });

        $fixedNumbers
            ->shouldBeLike(
                PlainNumbersArray::fromNumbers([
                    FixedNumber::fromString('1.00'),
                    FixedNumber::fromString('1.00'),
                    FixedNumber::fromString('1.24'),
                ])
            );

        $fixedNumbers
            ->map(function (FixedNumber $number): FixedNumber {
                return $number->withScale(0);
            })
            ->shouldBeLike(
                PlainNumbersArray::fromNumbers([
                    FixedNumber::fromString('1'),
                    FixedNumber::fromString('1'),
                    FixedNumber::fromString('1'),
                ])
            );
    }

    function it_flat_maps_numbers_set()
    {
        $this->beConstructedWithNumbers([
            FixedNumber::fromString('1.2'),
            FixedNumber::fromString('2.5'),
            FixedNumber::fromString('3.6'),
            FixedNumber::fromString('4.8'),
            FixedNumber::fromString('5.0'),
        ]);

        $this
            ->flatMap(function (NumberValue $number): array {
                $int = $number->floor();

                return [$int, $number->subtract($int)];
            })
            ->shouldBeLike(
                PlainNumbersArray::fromNumbers([
                    FixedNumber::fromString('1.0'),
                    FixedNumber::fromString('0.2'),
                    FixedNumber::fromString('2.0'),
                    FixedNumber::fromString('0.5'),
                    FixedNumber::fromString('3.0'),
                    FixedNumber::fromString('0.6'),
                    FixedNumber::fromString('4.0'),
                    FixedNumber::fromString('0.8'),
                    FixedNumber::fromString('5.0'),
                    FixedNumber::fromString('0.0'),
                ])
            );
    }

    function it_sorts_numbers_with_provided_comparator()
    {
        $this->beConstructedWithNumbers([
            $n6 = FixedNumber::fromString('100'),
            $n1 = FixedNumber::fromString('9'),
            $n4 = new IntegerNumber(80),
            $n3 = new IntegerNumber(70),
            $n5 = new FloatNumber(90.1),
            $n2 = new FloatNumber(9.1),
        ]);

        $this->sort(Comparators::numbers())
            ->shouldBeLike(PlainNumbersArray::fromNumbers([$n1, $n2, $n3, $n4, $n5, $n6]));
    }

    function it_sorts_numbers_with_provided_reversed_comparator()
    {
        $this->beConstructedWithNumbers([
            $n6 = FixedNumber::fromString('100'),
            $n1 = FixedNumber::fromString('9'),
            $n4 = new IntegerNumber(80),
            $n3 = new IntegerNumber(70),
            $n5 = new FloatNumber(90.1),
            $n2 = new FloatNumber(9.1),
        ]);

        $this->sort(Comparators::reversedNumbers())
            ->shouldBeLike(PlainNumbersArray::fromNumbers([$n6, $n5, $n4, $n3, $n2, $n1]));
    }

    function it_shuffles_numbers()
    {
        $numbers = range(1, 100);
        $this->beConstructedWithNumbers($numbers);
        $this->shuffle()->shouldNotBeLike(PlainNumbersArray::fromNumbers($numbers));
    }

    function it_reverses_numbers()
    {
        $numbers = [1, 2.0, 3, 4, 5];
        $this->beConstructedWithNumbers($numbers);
        $this->reverse()->shouldBeLike(PlainNumbersArray::fromNumbers(array_reverse($numbers)));
    }

    function it_unshift_numbers()
    {
        $numbers = [new IntegerNumber(3), new IntegerNumber(5), new IntegerNumber(8), new IntegerNumber(13)];
        $this->beConstructedWithNumbers($numbers);

        $n = new IntegerNumber(1);
        $this->unshift($n)->shouldBeLike(PlainNumbersArray::fromNumbers(array_merge([$n], $numbers)));
    }

    function it_shifts_numbers()
    {
        $numbers = [new IntegerNumber(3), new IntegerNumber(5), new IntegerNumber(8), new IntegerNumber(13)];
        $this->beConstructedWithNumbers($numbers);
        array_shift($numbers);

        $this->shift()->shouldBeLike(PlainNumbersArray::fromNumbers($numbers));
    }

    function it_pushes_number()
    {
        $numbers = [new IntegerNumber(3), new IntegerNumber(5), new IntegerNumber(8)];
        $this->beConstructedWithNumbers($numbers);

        $n = new IntegerNumber(13);
        $this->push($n)->shouldBeLike(PlainNumbersArray::fromNumbers(array_merge($numbers, [$n])));
    }

    function it_pops_number()
    {
        $numbers = [new IntegerNumber(1), new IntegerNumber(3), new IntegerNumber(5)];
        $this->beConstructedWithNumbers($numbers);
        array_pop($numbers);

        $this->pop()->shouldBeLike(PlainNumbersArray::fromNumbers($numbers));
    }

    function it_has_ArrayAccess_interface()
    {
        $numbers = [new IntegerNumber(1), new IntegerNumber(3), new IntegerNumber(5)];
        $this->beConstructedWithNumbers($numbers);

        $this->offsetExists(0)->shouldBe(true);
        $this[0]->shouldBe($numbers[0]);
        $this[2]->shouldBe($numbers[2]);
    }

    function it_joins_with_other_set_of_numbers()
    {
        $numbers = [new IntegerNumber(1), new IntegerNumber(3), new IntegerNumber(5)];
        $this->beConstructedWithNumbers($numbers);

        $this->join(Wrap::array([8, 13, 21]))->shouldBeLike(
            PlainNumbersArray::fromNumbers(
                array_merge($numbers, [new IntegerNumber(8), new IntegerNumber(13), new IntegerNumber(21)])
            )
        );
    }

    function it_can_be_sliced()
    {
        $numbers = [new IntegerNumber(1), new IntegerNumber(3), new IntegerNumber(5)];
        $this->beConstructedWithNumbers($numbers);

        $this->slice(0, 2)->shouldBeLike(PlainNumbersArray::fromNumbers([$numbers[0], $numbers[1]]));
        $this->slice(1, 2)->shouldBeLike(PlainNumbersArray::fromNumbers([$numbers[1], $numbers[2]]));
        $this->slice(-1, 1)->shouldBeLike(PlainNumbersArray::fromNumbers([$numbers[2]]));
    }

    private function beConstructedWithNumbers(array $numbers): void
    {
        $this->beConstructedThrough('fromNumbers', [$numbers]);
    }
}
