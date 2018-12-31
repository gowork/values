<?php

use GW\Value\Wrap;

$assoc = Wrap::assocArray(['a' => 1, 'b' => 2, 'c' => 3]);

echo 'assoc last: ' . $assoc->last();
