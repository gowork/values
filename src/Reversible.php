<?php

namespace GW\Value;

interface Reversible
{
    public function reverse(): Reversible;
}
