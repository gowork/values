<?php

namespace GW\Value;

interface Stream extends Mappable, Filterable
{
    public function map(callable $transformer): Stream;

    public function filter(callable $transformer): Stream;

    public function listen(callable $listener): Stream;
}
