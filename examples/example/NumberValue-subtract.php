<?php

use GW\Value\Numberable\JustFloat;
use GW\Value\Numberable\JustNumbers;
use GW\Value\Numberable\Sum;
use GW\Value\Wrap;

$number = Wrap::number(100);

echo "100 - 50 = ";
echo $number->subtract(50)->toNumber();
echo "\n";

echo "100 - 11.22 = ";
echo $number->subtract(new JustFloat(11.11))->toNumber();
echo "\n";

echo "100 - (10 + 20 + 30) = ";
echo $number->subtract(new Sum(new JustNumbers(10, 20, 30)))->toNumber();
echo "\n";
