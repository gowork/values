<?php

use GW\Value\Wrap;

$text = Wrap::string(' :.: I ♡ SPACE :.:  ');

echo $text->trimLeft()->toString() . PHP_EOL;
echo $text->trimLeft(' .:')->toString() . PHP_EOL;
