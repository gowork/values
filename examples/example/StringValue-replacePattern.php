<?php

use GW\Value\Wrap;

$text = Wrap::string('You are looking good! Really!');

echo $text->replacePattern('/[aeiouy]/', '')->toString();
