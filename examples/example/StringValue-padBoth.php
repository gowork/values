<?php

use GW\Value\Wrap;

$text = Wrap::string('☜ cut here ☞');

echo $text->padBoth(24, '-')->toString();
