<?php

use GW\Value\Wrap;

$text = Wrap::stringsArray(['Please', 'censor', 'all', 'vowels!']);

var_export($text->replacePattern('/[aeiouy]/', '*')->toArray());
