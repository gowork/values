<?php

use GW\Value\Strings;

$html = Strings::create('words don`t come easy');

echo $html->upperWords()->toString() . PHP_EOL;
