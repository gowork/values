<?php

use GW\Value\Wrap;

$one = Wrap::array(['a', 'b', 'c', 'd', 'e', 'f', 'g']);
$two = Wrap::array(['c', 'd', 'e', 'F']);

var_export($one->diff($two)->toArray());
echo PHP_EOL;

$lowercaseComparator = function(string $a, string $b): int {
    return mb_strtolower($a) <=> mb_strtolower($b);
};

var_export($one->diff($two, $lowercaseComparator)->toArray());
