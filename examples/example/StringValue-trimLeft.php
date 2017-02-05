<?php

use GW\Value\Strings;

$text = Strings::create(' :.: I ♡ SPACE :.:  ');

echo $text->trimLeft()->toString() . PHP_EOL;
echo $text->trimLeft(' .:')->toString() . PHP_EOL;
