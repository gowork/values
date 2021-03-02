<?php declare(strict_types=1);

namespace spec\GW\Value\Arrayable;

use GW\Value\Arrayable;
use PhpSpec\Exception\Example\FailureException;
use PhpSpec\ObjectBehavior;

final class CacheSpec extends ObjectBehavior
{
    function it_invoke_only_once_inner_arrayable()
    {
        $arrayable = new class implements Arrayable {
            private int $invokes = 0;
            public function toArray(): array
            {
                $this->invokes++;
                if ($this->invokes > 1) {
                    throw new FailureException('Should be invoked once');
                }

                return ['hello', 'world'];
            }
        };

        $this->beConstructedWith($arrayable);

        $this->toArray()->shouldBe(['hello', 'world']);
        $this->toArray()->shouldBe(['hello', 'world']);
        $this->toArray()->shouldBe(['hello', 'world']);
    }
}
