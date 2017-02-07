<?php

use GW\Value\Wrap;

$text = Wrap::string('words don`t come easy');

echo $text->upperFirst()->toString() . PHP_EOL;
