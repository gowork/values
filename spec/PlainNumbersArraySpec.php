<?php

namespace spec\GW\Value;

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

    function it_can_calculate_average_number()
    {
        $this->beConstructedWithNumbers([1, 2, 3, 4, 5]);
        $this->avg()->toInt()->shouldBe(3);
        $this->avg()->toFloat()->shouldBe(3.0);
    }

    private function beConstructedWithNumbers(array $numbers): void
    {
        $this->beConstructedThrough('fromNumbers', [$numbers]);
    }
}
