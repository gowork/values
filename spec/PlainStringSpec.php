<?php 

namespace spec\GW\Value;

use GW\Value\PlainArray;
use GW\Value\PlainString;
use PhpSpec\ObjectBehavior;

final class PlainStringSpec extends ObjectBehavior 
{
    function it_is_initializable()
    {
        $this->beConstructedWith('string');
        $this->shouldHaveType(PlainString::class);
    }

    function it_can_be_casted_to_string()
    {
        $this->beConstructedWith('example');
        $this->__toString()->shouldReturn('example');
        $this->toString()->shouldReturn('example');
        $this->value()->shouldReturn('example');
    }

    function it_gets_a_substring()
    {
        $this->beConstructedWith('seashell');

        $substring = $this->substring(0, 3);
        $substring->shouldNotBe($this);
        $substring->shouldBeLike(new PlainString('sea'));
    }

    function it_gets_a_substring_from_start_to_en_when_length_is_not_defined()
    {
        $this->beConstructedWith('seashell');

        $this->substring(3)->shouldBeLike(new PlainString('shell'));
    }

    function it_strips_tags()
    {
        $this->beConstructedWith('<p>This is a paragraph.</p>');

        $stripped = $this->stripTags();
        $stripped->shouldNotBe($this);
        $stripped->shouldBeLike(new PlainString('This is a paragraph.'));
    }

    function it_trims_white_characters()
    {
        $this->beConstructedWith(" \t   example \n\n\n\t ");

        $trimmed = $this->trim();
        $trimmed->shouldNotBe($this);
        $trimmed->shouldBeLike(new PlainString('example'));
    }

    function it_trims_provided_characters()
    {
        $this->beConstructedWith(" .. . example ... ");

        $this->trim(' .')->shouldBeLike(new PlainString('example'));
    }

    function it_trims_right_white_characters()
    {
        $this->beConstructedWith(" \t   example \n\n\n\t ");

        $trimmed = $this->trimRight();
        $trimmed->shouldNotBe($this);
        $trimmed->shouldBeLike(new PlainString(" \t   example"));
    }

    function it_trims_right_provided_characters()
    {
        $this->beConstructedWith(" .. . example ... ");

        $this->trimRight(' .')->shouldBeLike(new PlainString(" .. . example"));
    }

    function it_trims_left_white_characters()
    {
        $this->beConstructedWith(" \t   example \n\n\n\t ");

        $trimmed = $this->trimLeft();
        $trimmed->shouldNotBe($this);
        $trimmed->shouldBeLike(new PlainString("example \n\n\n\t "));
    }

    function it_trims_left_provided_characters()
    {
        $this->beConstructedWith(" .. . example . .. ");

        $this->trimLeft(' .')->shouldBeLike(new PlainString("example . .. "));
    }

    function it_converts_string_to_lower_case()
    {
        $this->beConstructedWith('WILL Will Smith smith?');

        $lower = $this->lower();
        $lower->shouldNotBe($this);
        $lower->shouldBeLike(new PlainString('will will smith smith?'));
    }

    function it_converts_national_characters_to_lower_case()
    {
        $this->beConstructedWith('ZAŻÓŁĆ GĘŚLĄ JAŹŃ');

        $this->lower()->shouldBeLike(new PlainString('zażółć gęślą jaźń'));
    }

    function it_converts_string_to_upper_case()
    {
        $this->beConstructedWith('will will smith smith?');

        $upper = $this->upper();
        $upper->shouldNotBe($this);
        $upper->shouldBeLike(new PlainString('WILL WILL SMITH SMITH?'));
    }

    function it_converts_national_characters_to_upper_case()
    {
        $this->beConstructedWith('zażółć gęślą jaźń');

        $this->upper()->shouldBeLike(new PlainString('ZAŻÓŁĆ GĘŚLĄ JAŹŃ'));
    }

    function it_converts_first_letter_to_lower_case()
    {
        $this->beConstructedWith('Will');

        $lower = $this->lowerFirst();
        $lower->shouldNotBe($this);
        $lower->shouldBeLike(new PlainString('will'));
    }

    function it_converts_first_letter_to_lower_case_with_national_characters()
    {
        $this->beConstructedWith('Źrebak');

        $this->lowerFirst()->shouldBeLike(new PlainString('źrebak'));
    }

    function it_converts_first_letter_to_upper_case()
    {
        $this->beConstructedWith('will will smith smith?');

        $upper = $this->upperFirst();
        $upper->shouldNotBe($this);
        $upper->shouldBeLike(new PlainString('Will will smith smith?'));
    }

    function it_converts_first_letter_to_upper_case_with_national_characters()
    {
        $this->beConstructedWith('śmieszny żółty źrebak');

        $this->upperFirst()->shouldBeLike(new PlainString('Śmieszny żółty źrebak'));
    }

    function it_converts_first_letter_to_upper_case_in_words()
    {
        $this->beConstructedWith('will will smith smith?');

        $upper = $this->upperWords();
        $upper->shouldNotBe($this);
        $upper->shouldBeLike(new PlainString('Will Will Smith Smith?'));
    }

    function it_converts_first_letter_to_upper_case_in_words_with_national_characters()
    {
        $this->beConstructedWith('śmieszny żółty źrebak ćwiczył ósemki');

        $this->upperWords()->shouldBeLike(new PlainString('Śmieszny Żółty Źrebak Ćwiczył Ósemki'));
    }

    function it_pads_string_right_with_space_to_specified_length()
    {
        $this->beConstructedWith('short');

        $padded = $this->padRight(12);
        $padded->shouldNotBe($this);
        $padded->shouldBeLike(new PlainString('short       '));
    }

    function it_pads_string_right_with_space_to_specified_length_with_string()
    {
        $this->beConstructedWith('short');

        $padded = $this->padRight(12, '-');
        $padded->shouldNotBe($this);
        $padded->shouldBeLike(new PlainString('short-------'));
    }

    function it_pads_string_left_with_space_to_specified_length()
    {
        $this->beConstructedWith('short');

        $padded = $this->padLeft(12);
        $padded->shouldNotBe($this);
        $padded->shouldBeLike(new PlainString('       short'));
    }

    function it_pads_string_left_with_space_to_specified_length_with_string()
    {
        $this->beConstructedWith('short');

        $padded = $this->padLeft(12, '-');
        $padded->shouldNotBe($this);
        $padded->shouldBeLike(new PlainString('-------short'));
    }

    function it_pads_string_both_with_space_to_specified_length()
    {
        $this->beConstructedWith('short');

        $padded = $this->padBoth(12);
        $padded->shouldNotBe($this);
        $padded->shouldBeLike(new PlainString('   short    '));
    }

    function it_pads_string_both_with_space_to_specified_length_with_string()
    {
        $this->beConstructedWith('short');

        $padded = $this->padBoth(12, '-');
        $padded->shouldNotBe($this);
        $padded->shouldBeLike(new PlainString('---short----'));
    }

    function it_replaces_substring_in_text()
    {
        $this->beConstructedWith('She sells seashells by the seashore');

        $replaced = $this->replace('sea', 'nut ');
        $replaced->shouldNotBe($this);
        $replaced->shouldBeLike(new PlainString('She sells nut shells by the nut shore'));
    }

    function it_replaces_by_pattern()
    {
        $this->beConstructedWith('She sells seashells by the seashore');

        $replaced = $this->replacePattern('/sea[a-z]+/', '*');
        $replaced->shouldNotBe($this);
        $replaced->shouldBeLike(new PlainString('She sells * by the *'));
    }

    function it_replaces_by_pattern_with_callback(CallableMock $transformer)
    {
        $this->beConstructedWith('She sells seashells by the seashore');

        $transformer->__invoke(['seashells'])->willReturn('x');
        $transformer->__invoke(['seashore'])->willReturn('y');

        $replaced = $this->replacePatternCallback('/sea[a-z]+/', $transformer);
        $replaced->shouldNotBe($this);
        $replaced->shouldBeLike(new PlainString('She sells x by the y'));
    }

    function it_truncates_long_text()
    {
        $this->beConstructedWith('She sells seashells by the seashore');

        $truncated = $this->truncate(3);
        $truncated->shouldNotBe($this);
        $truncated->shouldBeLike(new PlainString('She...'));
    }

    function it_truncates_long_text_with_given_postfix()
    {
        $this->beConstructedWith('She sells seashells by the seashore');

        $this->truncate(3, '~')->shouldBeLike(new PlainString('She~'));
    }

    function it_gets_string_length()
    {
        $this->beConstructedWith('How are you doing.');

        $this->length()->shouldReturn(18);
    }

    function it_gets_string_length_with_national_characters()
    {
        $this->beConstructedWith('Zażółć gęślą jaźń.');

        $this->length()->shouldReturn(18);
    }

    function it_gets_substring_position()
    {
        $this->beConstructedWith('How are you doing? How are you walking?');

        $this->position('are')->shouldReturn(4);
        $this->positionLast('are')->shouldReturn(23);
    }

    function it_gets_substring_position_with_national_characters()
    {
        $this->beConstructedWith('Zażółć gęślą jaźń. Zabiel gęślą zupę.');

        $this->position('gęś')->shouldReturn(7);
        $this->positionLast('gęś')->shouldReturn(26);
    }

    function it_returns_null_position_when_substring_not_found()
    {
        $this->beConstructedWith('How are you doing.');

        $this->position('zonk')->shouldReturn(null);
        $this->positionLast('zonk')->shouldReturn(null);
    }

    function it_accepts_custom_transformer_working_on_primitive_string()
    {
        $this->beConstructedWith('Hide my secret!');

        $transformer = function (string $value): string {
            return md5($value);
        };

        $transformed = $this->transform($transformer);
        $transformed->shouldNotBe($this);
        $transformed->shouldBeLike(new PlainString(md5('Hide my secret!')));
    }

    function it_returns_ArrayValue_containing_regex_matches()
    {
        $this->beConstructedWith('<b>Lorem</b> ipsum dolor sit <i>amet</i>');

        $matches = $this->matchAllPatterns('#<[^>]+>(lorem|amet)</[^>]+>#ui');
        $matches->shouldNotBe($this);
        $matches->shouldBeLike(new PlainArray([['<b>Lorem</b>', 'Lorem'], ['<i>amet</i>', 'amet']]));
    }

    function it_can_check_if_starting_with()
    {
        $this->beConstructedWith('lorem ipsum');
        $this->isStartingWith('lorem')->shouldBe(true);
        $this->isStartingWith('l')->shouldBe(true);
        $this->isStartingWith(' lorem')->shouldBe(false);
        $this->isStartingWith('ipsum')->shouldBe(false);
    }
}
