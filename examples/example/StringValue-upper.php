<?php

use GW\Value\Wrap;

$text = Wrap::string('it`s so quiet...');

echo $text->upper()->toString() . PHP_EOL;
