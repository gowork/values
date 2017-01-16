<?php

namespace GW\Value;

interface Stream
{
    public function map(callable $transformer): Stream;

    public function filter(callable $transformer): Stream;

    public function listen(callable $listener): Stream;
}
