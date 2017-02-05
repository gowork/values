<?php

use GW\Value\Strings;

$text = Strings::create('words don`t come easy');

echo $text->upperFirst()->toString() . PHP_EOL;
