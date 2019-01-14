<?php declare(strict_types=1);

namespace GW\Value;

interface Slicable extends \IteratorAggregate
{
    /**
     * @return Slicable
     */
    public function slice(int $offset, int $length);
}
