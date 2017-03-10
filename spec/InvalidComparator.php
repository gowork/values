<?php

namespace spec\GW\Value;

class InvalidComparator
{
    public function __invoke(): bool
    {
        return true;
    }
}
