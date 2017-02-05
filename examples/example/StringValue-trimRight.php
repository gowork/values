<?php

use GW\Value\Strings;

$text = Strings::create(' :.: I ♡ SPACE :.:  ');

echo $text->trimRight()->toString() . PHP_EOL;
echo $text->trimRight(' .:')->toString() . PHP_EOL;
