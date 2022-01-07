<?php

use GW\Value\Numberable\JustFloat;
use GW\Value\Numberable\Sum;
use GW\Value\Numberable\WrapNumbers;
use GW\Value\Wrap;

$number = Wrap::number(100);

echo "100 + 50 = ";
echo $number->add(50)->toNumber();
echo "\n";

echo "100 + 11.22 = ";
echo $number->add(new JustFloat(11.22))->toNumber();
echo "\n";

echo "100 + (10 + 20 + 30) = ";
echo $number->add(new Sum(new WrapNumbers(10, 20, 30)))->toNumber();
echo "\n";
