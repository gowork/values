<?php

namespace spec\GW\Value;

use GW\Value\Arrays;
use GW\Value\Strings;
use GW\Value\StringsArray;
use GW\Value\StringValue;
use PhpSpec\ObjectBehavior;

final class StringsArraySpec extends ObjectBehavior
{
    function it_is_initializable_with_primitive_string()
    {
        $this->beConstructedWithStrings('string');

        $this->shouldHaveType(StringsArray::class);

        $this->count()->shouldReturn(1);
        $this->toString()->shouldReturn('string');
    }

    function it_is_initializable_with_string_value()
    {
        $this->beConstructedWith(Arrays::create([Strings::create('string')]));

        $this->shouldHaveType(StringsArray::class);

        $this->count()->shouldReturn(1);
        $this->toString()->shouldReturn('string');
    }

    function it_joins_with_other_StringsArray()
    {
        $this->beConstructedWithStrings('string 1', 'string 2');

        $join = $this->join(StringsArray::fromArray(['string 3', 'string 4']));
        $join->shouldNotBe($this);
        $join->shouldBeLike(StringsArray::fromArray(['string 1', 'string 2', 'string 3', 'string 4']));
    }

    function it_joins_with_regular_ArrayValue_with_strings()
    {
        $this->beConstructedWithStrings('string 1', 'string 2');

        $this->join(Arrays::create(['string 3', 'string 4']))
            ->shouldBeLike(StringsArray::fromArray(['string 1', 'string 2', 'string 3', 'string 4']));
    }

    function it_returns_a_slice()
    {
        $this->beConstructedWithStrings('string 1', 'string 2', 'string 3', 'string 4');

        $this->slice(0, 1)->shouldNotBe($this);
        $this->slice(0, 1)->shouldBeLike(StringsArray::fromArray(['string 1']));
        $this->slice(3, 1)->shouldBeLike(StringsArray::fromArray(['string 4']));
        $this->slice(1, 2)->shouldBeLike(StringsArray::fromArray(['string 2', 'string 3']));
        $this->slice(0, 4)->shouldBeLike($this);
        $this->slice(0, 500)->shouldBeLike($this);
    }

    function it_returns_diff()
    {
        $this->beConstructedWithStrings('string 1', 'string 2', 'string 3', 'string 4');

        $diff = $this->diff(StringsArray::fromArray(['string 2', 'string 3']));
        $diff->shouldNotBe($this);
        $diff->shouldBeLike(StringsArray::fromArray(['string 1', 'string 4']));

        $this->diff(StringsArray::fromArray(['string 1', 'string 2', 'string 3', 'string 4']))
            ->shouldBeLike(StringsArray::fromArray([]));

        $this->diff(StringsArray::fromArray([]))->shouldBeLike($this);
    }

    function it_returns_intersection()
    {
        $this->beConstructedWithStrings('string 1', 'string 2', 'string 3', 'string 4');

        $intersect = $this->intersect(StringsArray::fromArray(['string 2', 'string 3']));
        $intersect->shouldNotBe($this);
        $intersect->shouldBeLike(StringsArray::fromArray(['string 2', 'string 3']));

        $this->intersect(StringsArray::fromArray(['string 1', 'string 2', 'string 3', 'string 4']))
            ->shouldBeLike($this);

        $this->intersect(StringsArray::fromArray([]))->shouldBeLike(StringsArray::fromArray([]));
    }

    function it_reduces_array_to_single_value()
    {
        $this->beConstructedWithStrings('c', 'o', 'o', 'l');

        $reducer = function(StringValue $reduced, StringValue $value) {
            return $reduced->postfix($value);
        };

        $this->reduce($reducer, Strings::create('so '))->shouldBeLike(Strings::create('so cool'));
    }

    function it_maps_values_to_another_StringsArray()
    {
        $this->beConstructedWithStrings('aaa', 'bbb', 'ccc', 'ddd');

        $mapper = function(StringValue $value): StringValue {
            return $value->upper();
        };

        $this->map($mapper)->shouldBeLike(StringsArray::fromArray(['AAA', 'BBB', 'CCC', 'DDD']));
    }

    function it_maps_values_to_another_StringsArray_with_mapper_returning_string()
    {
        $this->beConstructedWithStrings('aaa', 'bbb', 'ccc', 'ddd');

        $mapper = function(StringValue $value): string {
            return $value->upper()->toString();
        };

        $this->map($mapper)->shouldBeLike(StringsArray::fromArray(['AAA', 'BBB', 'CCC', 'DDD']));
    }

    function it_returns_first_and_last()
    {
        $this->beConstructedWithStrings('first', 'second', 'third');

        $this->first()->shouldBeLike(Strings::create('first'));
        $this->last()->shouldBeLike(Strings::create('third'));
    }

    function it_returns_null_from_first_and_last_method_when_collection_is_empty()
    {
        $this->beConstructedWithStrings();

        $this->first()->shouldReturn(null);
        $this->last()->shouldReturn(null);
    }

    function it_calls_a_function_on_each_element(CallableMock $callable)
    {
        $this->beConstructedWithStrings('first', 'second', 'third');

        $this->each($callable);

        $callable->__invoke(Strings::create('first'))->shouldHaveBeenCalled();
        $callable->__invoke(Strings::create('second'))->shouldHaveBeenCalled();
        $callable->__invoke(Strings::create('third'))->shouldHaveBeenCalled();
    }

    function it_returns_StringsArray_with_unique_values()
    {
        $this->beConstructedWithStrings('first', 'first','second', 'third');

        $unique = $this->unique();
        $unique->shouldNotBe($this);
        $unique->shouldBeLike(StringsArray::fromArray(['first', 'second', 'third']));
    }

    function it_returns_StringsArray_with_unique_values_using_comparator()
    {
        $this->beConstructedWithStrings('first', 'FIRST','second');

        $this->unique()->shouldBeLike(StringsArray::fromArray(['first', 'FIRST', 'second']));

        $lowerComparator = function(StringValue $valueA, StringValue $valueB): int {
            return $valueA->lower() <=> $valueB->lower();
        };

        $this->unique($lowerComparator)->shouldBeLike(StringsArray::fromArray(['first', 'second']));
    }

    function it_returns_regular_array()
    {
        $this->beConstructedWithStrings('first', 'second', 'third');

        $this->toArray()
            ->shouldBeLike([Strings::create('first'), Strings::create('second'), Strings::create('third')]);
    }

    function it_trims_all_contained_strings()
    {
        $this->beConstructedWith(Arrays::create(['  string1  ', '  string2  ']));

        $this->trim()->shouldBeLike(StringsArray::fromArray(['string1', 'string2']));
    }

    private function beConstructedWithStrings(string ...$strings): void
    {
        $this->beConstructedWith(Arrays::create($strings));
    }
}
