<?php

use GW\Value\Wrap;

$text = Wrap::string('CamelCaseMethod()');

echo $text->lowerFirst()->toString() . PHP_EOL;
