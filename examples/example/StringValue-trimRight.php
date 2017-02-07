<?php

use GW\Value\Wrap;

$text = Wrap::string(' :.: I ♡ SPACE :.:  ');

echo $text->trimRight()->toString() . PHP_EOL;
echo $text->trimRight(' .:')->toString() . PHP_EOL;
