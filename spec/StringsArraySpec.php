<?php

namespace spec\GW\Value;

use GW\Value\Arrays;
use GW\Value\ArrayValue;
use GW\Value\Sorts;
use GW\Value\Strings;
use GW\Value\StringsArray;
use GW\Value\StringValue;
use PhpSpec\Exception\Example\FailureException;
use PhpSpec\ObjectBehavior;
use PhpSpec\Wrapper\Collaborator;
use Prophecy\Argument;

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

        $reducer = function (StringValue $reduced, StringValue $value) {
            return $reduced->postfix($value);
        };

        $this->reduce($reducer, Strings::create('so '))->shouldBeLike(Strings::create('so cool'));
    }

    function it_maps_values_to_another_StringsArray()
    {
        $this->beConstructedWithStrings('aaa', 'bbb', 'ccc', 'ddd');

        $mapper = function (StringValue $value): StringValue {
            return $value->upper();
        };

        $this->map($mapper)->shouldBeLike(StringsArray::fromArray(['AAA', 'BBB', 'CCC', 'DDD']));
    }

    function it_maps_values_to_another_StringsArray_with_mapper_returning_string()
    {
        $this->beConstructedWithStrings('aaa', 'bbb', 'ccc', 'ddd');

        $mapper = function (StringValue $value): string {
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
        $this->beConstructedWithStrings('first', 'first', 'second', 'third');

        $unique = $this->unique();
        $unique->shouldNotBe($this);
        $unique->shouldBeLike(StringsArray::fromArray(['first', 'second', 'third']));
    }

    function it_returns_StringsArray_with_unique_values_using_comparator()
    {
        $this->beConstructedWithStrings('first', 'FIRST', 'second');

        $this->unique()->shouldBeLike(StringsArray::fromArray(['first', 'FIRST', 'second']));

        $lowerComparator = function (StringValue $valueA, StringValue $valueB): int {
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

    function it_filters_empty_strings()
    {
        $this->beConstructedWithStrings('first', '', ' ', 'second', '0', 'false');

        $this->count()->shouldReturn(6);

        $notEmpty = $this->filterEmpty();

        $notEmpty->shouldNotBe($this);
        $notEmpty->shouldBeLike(StringsArray::fromArray(['first', ' ', 'second', '0', 'false']));
    }

    function it_sorts_ascending()
    {
        $this->beConstructedWithStrings('beta', 'alpha', 'zeta', 'omega');

        $sorted = $this->sort(Sorts::asc());
        $sorted->shouldNotBeLike($this);
        $sorted->shouldBeLike(StringsArray::fromArray(['alpha', 'beta', 'omega', 'zeta']));
    }

    function it_sorts_descending()
    {
        $this->beConstructedWithStrings('beta', 'alpha', 'zeta', 'omega');

        $sorted = $this->sort(Sorts::desc());
        $sorted->shouldNotBeLike($this);
        $sorted->shouldBeLike(StringsArray::fromArray(['zeta', 'omega', 'beta', 'alpha']));
    }

    function it_shuffles_string_items(ArrayValue $strings)
    {
        $this->beConstructedWith($strings);

        $this->prepareArrayCollaborator($strings);

        $strings->shuffle()->shouldBeCalled()->willReturn($strings);

        $shuffled = $this->shuffle();
        $shuffled->shouldNotBe($this);
        $shuffled->shouldHaveType(StringsArray::class);
    }

    function it_reverses_containing_string()
    {
        $this->beConstructedWithStrings('Yoda', 'is', 'name', 'My');

        $reversed = $this->reverse();
        $reversed->shouldNotBe($this);
        $reversed->shouldBeLike(StringsArray::fromArray(['My', 'name', 'is', 'Yoda']));
    }

    function it_can_be_iterated()
    {
        $this->beConstructedWithStrings('first', 'second');

        $this->shouldImplement(\IteratorAggregate::class);
        $this->getIterator()->shouldImplement(\Iterator::class);
    }

    function it_is_like_array()
    {
        $this->beConstructedWithStrings('first', 'second');

        $this->shouldImplement(\ArrayAccess::class);

        $this[0]->shouldBeLike(Strings::create('first'));
        $this->offsetGet(0)->shouldBeLike(Strings::create('first'));

        $this->offsetExists(0)->shouldReturn(true);
        $this->offsetExists(2)->shouldReturn(false);
    }

    function it_is_like_array_but_immutable()
    {
        $this->beConstructedWithStrings('first', 'second');

        $this->shouldThrow(\BadMethodCallException::class)->during('offsetSet', [0, Strings::create('mutant')]);
        $this->shouldThrow(\BadMethodCallException::class)->during('offsetUnset', [0]);
    }

    function it_returns_count_of_contained_strings()
    {
        $this->beConstructedWithStrings('one', 'two', 'three', 'four', 'five');

        $this->count()->shouldReturn(5);
    }

    function it_prepends_string()
    {
        $this->beConstructedWithStrings('one', 'two', 'three');

        $added = $this->unshift('zero');
        $added->shouldNotBe($this);
        $added->shouldBeLike(StringsArray::fromArray(['zero', 'one', 'two', 'three']));
    }

    function it_prepends_StringValue()
    {
        $this->beConstructedWithStrings('one', 'two', 'three');

        $added = $this->unshift(Strings::create('zero'));
        $added->shouldNotBe($this);
        $added->shouldBeLike(StringsArray::fromArray(['zero', 'one', 'two', 'three']));
    }

    function it_shift_item_from_the_beginning_of_strings_array()
    {
        $this->beConstructedWithStrings('one', 'two');
        $clone = StringsArray::fromArray(['one', 'two']);

        $reduced = $this->shift();
        $reduced->shouldBeLike($clone->shift($one));

        if ($one != Strings::create('one')) {
            throw new FailureException('Shifted value should be assigned to provided variable');
        }

        $reduced->count()->shouldReturn(1);
    }

    function it_appends_string()
    {
        $this->beConstructedWithStrings('one', 'two');

        $appended = $this->push('three');
        $appended->shouldNotBe($this);
        $appended->shouldBeLike(StringsArray::fromArray(['one', 'two', 'three']));
    }

    function it_appends_StringValue()
    {
        $this->beConstructedWithStrings('one', 'two');

        $appended = $this->push(Strings::create('three'));
        $appended->shouldNotBe($this);
        $appended->shouldBeLike(StringsArray::fromArray(['one', 'two', 'three']));
    }

    function it_pops_string_from_the_end_of_array()
    {
        $this->beConstructedWithStrings('one', 'two', 'last');
        $clone = StringsArray::fromArray(['one', 'two', 'last']);

        $popped = $this->pop();
        $popped->shouldBeLike($clone->pop($last));

        if ($last != Strings::create('last')) {
            throw new FailureException('Popped value should be assigned to provided variable');
        }

        $this->count()->shouldReturn(3);
        $popped->count()->shouldReturn(2);
    }

    function it_strip_tags_from_all_strings()
    {
        $this->beConstructedWithStrings(
            '<h1>This is a header</h1>',
            '<h3>This is a subtitle</h3>',
            "<javascript> alert('This is javascript'); </javascript>"
        );

        $stripped = $this->stripTags();
        $stripped->shouldNotBe($this);
        $stripped->shouldBeLike(
            StringsArray::fromArray(['This is a header', 'This is a subtitle', ' alert(\'This is javascript\'); '])
        );
    }

    function it_trims_all_contained_strings()
    {
        $this->beConstructedWithStrings(" \t\n one  ", "  two \n\r ");

        $this->trim()->shouldBeLike(StringsArray::fromArray(['one', 'two']));
    }

    function it_trims_all_contained_strings_with_custom_characters()
    {
        $this->beConstructedWithStrings(' xxx one xxx ', 'xxxtwoxxx', ' three ');

        $this->trim(' x')->shouldBeLike(StringsArray::fromArray(['one', 'two', 'three']));
    }

    function it_trims_right_all_contained_strings()
    {
        $this->beConstructedWithStrings(" \t\n one  ", "  two \n\r ");

        $this->trimRight()->shouldBeLike(StringsArray::fromArray([" \t\n one", "  two"]));
    }

    function it_trims_right_all_contained_strings_with_custom_characters()
    {
        $this->beConstructedWithStrings(' xxx one xxx ', 'xxxtwoxxx', ' three ');

        $this->trimRight(' x')->shouldBeLike(StringsArray::fromArray([' xxx one', 'xxxtwo', ' three']));
    }

    function it_trims_left_all_contained_strings()
    {
        $this->beConstructedWithStrings(" \t\n one  ", "  two \n\r ");

        $this->trimLeft()->shouldBeLike(StringsArray::fromArray(["one  ", "two \n\r "]));
    }

    function it_trims_left_all_contained_strings_with_custom_characters()
    {
        $this->beConstructedWithStrings(' xxx one xxx ', 'xxxtwoxxx', ' three ');

        $this->trimLeft(' x')->shouldBeLike(StringsArray::fromArray(['one xxx ', 'twoxxx', 'three ']));
    }

    function it_transforms_strings_to_lower_case()
    {
        $this->beConstructedWithStrings('Will', 'Will', 'SMITH', 'smith?');

        $lower = $this->lower();
        $lower->shouldNotBe($this);
        $lower->shouldBeLike(StringsArray::fromArray(['will', 'will', 'smith', 'smith?']));
    }

    function it_transforms_national_characters_to_lower_case()
    {
        $this->beConstructedWithStrings('zaŻÓŁĆ', 'gĘślĄ', 'jaŹŃ');

        $lower = $this->lower();
        $lower->shouldNotBe($this);
        $lower->shouldBeLike(StringsArray::fromArray(['zażółć', 'gęślą', 'jaźń']));
    }

    function it_transforms_strings_to_upper_case()
    {
        $this->beConstructedWithStrings('will', 'will', 'smith', 'smith?');

        $upper = $this->upper();
        $upper->shouldNotBe($this);
        $upper->shouldBeLike(StringsArray::fromArray(['WILL', 'WILL', 'SMITH', 'SMITH?']));
    }

    function it_transforms_national_characters_to_upper_case()
    {
        $this->beConstructedWithStrings('zażółć', 'gęślą', 'jaźń');

        $upper = $this->upper();
        $upper->shouldNotBe($this);
        $upper->shouldBeLike(StringsArray::fromArray(['ZAŻÓŁĆ', 'GĘŚLĄ', 'JAŹŃ']));
    }

    function it_transforms_first_letter_to_lower_case_in_all_strings()
    {
        $this->beConstructedWithStrings('ŻABA', 'MEANS', 'FROG');

        $lower = $this->lowerFirst();
        $lower->shouldNotBe($this);
        $lower->shouldBeLike(StringsArray::fromArray(['żABA', 'mEANS', 'fROG']));
    }

    function it_transforms_first_letter_to_upper_case_in_all_strings()
    {
        $this->beConstructedWithStrings('żaba', 'means', 'frog');

        $lower = $this->upperFirst();
        $lower->shouldNotBe($this);
        $lower->shouldBeLike(StringsArray::fromArray(['Żaba', 'Means', 'Frog']));
    }

    private function beConstructedWithStrings(string ...$strings): void
    {
        $this->beConstructedWith(Arrays::create($strings));
    }

    /**
     * @param ArrayValue|Collaborator $strings
     */
    private function prepareArrayCollaborator($strings): void
    {
        $strings->map(Argument::type('callable'))->willReturn($strings);
        $strings->each(Argument::type('callable'))->willReturn($strings);
    }
}
