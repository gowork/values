<?php

use GW\Value\NumberValue;
use GW\Value\Wrap;

$numbers = Wrap::numbersArray([1, 2, 3, 4, 5]);

echo "5! = ";
echo $numbers
    ->reduceNumber(
        fn (NumberValue $factorial, NumberValue $next): NumberValue => $factorial->multiply($next),
        1
    )
    ->toNumber();
echo "\n";
