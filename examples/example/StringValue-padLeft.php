<?php

use GW\Value\Wrap;

$text = Wrap::string('☜ cut here');

echo $text->padLeft(16, '-')->toString();
