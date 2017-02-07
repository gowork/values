<?php

use GW\Value\Wrap;

$text = Wrap::stringsArray(['it`s so quiet', 'and peaceful']);

var_export($text->upper()->toArray());
