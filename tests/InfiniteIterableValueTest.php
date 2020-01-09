<?php declare(strict_types=1);

namespace tests\GW\Value;

use GW\Value\Wrap;
use PHPUnit\Framework\TestCase;

final class InfiniteIterableValueTest extends TestCase
{
    function test_memleak()
    {
        $iterable = Wrap::iterable(
            $cls = new class() implements \IteratorAggregate {
                public function getIterator(): iterable
                {
                    $i = 0;
                    while (true) {
                        yield $i++;
                    }
                }
            }
        );

        $weak = \WeakReference::create($cls);
        $items = [];

        $mapped = $iterable
            ->slice(12, 10)
            ->map(
                static function ($i) {
                    return $i + 9;
                }
            );

        foreach ($mapped as $item) {
            $items[] = $item;
        }

        unset($cls, $iterable, $mapped);

        self::assertCount(10, $items);
        self::assertEquals([21, 22, 23, 24, 25, 26, 27, 28, 29, 30], $items);
        self::assertNull($weak->get(), 'Iterator object should be freed');
    }
}
