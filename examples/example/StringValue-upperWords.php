<?php

use GW\Value\Wrap;

$html = Wrap::string('words don`t come easy');

echo $html->upperWords()->toString() . PHP_EOL;
