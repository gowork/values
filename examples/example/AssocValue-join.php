<?php

use GW\Value\Wrap;

$one = Wrap::assocArray(['a' => 1, 'b' => 2, 'c' => 3]);
$two = Wrap::assocArray(['c' => 5, 'd' => 4]);

var_export($one->join($two)->toAssocArray());
