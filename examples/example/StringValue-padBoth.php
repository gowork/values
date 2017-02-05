<?php

use GW\Value\Strings;

$text = Strings::create('☜ cut here ☞');

echo $text->padBoth(24, '-')->toString();
