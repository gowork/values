<?php

use GW\Value\Arrays;

$one = Arrays::create(['a', 'b', 'c', 'd', 'e', 'f', 'g']);
$two = Arrays::create(['c', 'd', 'e', 'F']);

var_export($one->diff($two)->toArray());

$lowercaseComparator = function(string $a, string $b): int {
    return mb_strtolower($a) <=> mb_strtolower($b);
};

var_export($one->diff($two, $lowercaseComparator)->toArray());
