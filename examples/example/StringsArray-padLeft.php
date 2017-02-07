<?php

use GW\Value\Wrap;

$text = Wrap::stringsArray(['one', 'two', 'three']);

var_export($text->padLeft(16, '-')->toArray());
