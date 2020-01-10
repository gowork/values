<?php

use GW\Value\Wrap;

$text = Wrap::string('Your favourite colors are red and black');

echo $text->replaceAll(['red', 'black'], 'blue')->toString();
