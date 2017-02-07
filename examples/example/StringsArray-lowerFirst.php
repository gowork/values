<?php

use GW\Value\Wrap;

$text = Wrap::stringsArray(['CamelCaseMethod', 'AnotherCamel']);

var_export($text->lowerFirst()->toArray());
