<?php

use GW\Value\Strings;

$text = Strings::create('it`s so quiet...');

echo $text->upper()->toString() . PHP_EOL;
