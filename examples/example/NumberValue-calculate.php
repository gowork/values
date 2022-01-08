<?php

use GW\Value\Numberable;
use GW\Value\Numberable\JustInteger;
use GW\Value\Numberable\Math;
use GW\Value\Numberable\Multiply;
use GW\Value\Wrap;

$number = Wrap::number(100);

echo "100 * 12 = ";
echo $number
    ->calculate(fn(Numberable $number): Numberable => new Multiply($number, new JustInteger(12)))
    ->toNumber();
echo "\n";

echo "cos(100) = ";
echo $number->calculate(Math::cos())->toNumber();
echo "\n";

echo "√100 = ";
echo $number->calculate(Math::sqrt())->toNumber();
echo "\n";
