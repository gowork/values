<?php declare(strict_types=1);

namespace GW\Value;

interface Slicable
{
    /**
     * @return Slicable
     */
    public function slice(int $offset, int $length);
}
