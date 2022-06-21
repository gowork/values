<?php

use GW\Value\Wrap;

$assoc = Wrap::assocArray(['0' => 'zero', '1' => 'one']);

$keys = $assoc
    ->map(fn(string $val, int $key): string => $val)
    ->keys()
    ->toArray();

var_export($keys);
