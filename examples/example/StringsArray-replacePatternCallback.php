<?php

use GW\Value\Wrap;

$text = Wrap::stringsArray(['Please', 'censor', 'all', 'vowels!']);

$replacer = function (array $match): string {
    $letter = $match[0];

    return '(' . $letter . ')';
};

var_export($text->replacePatternCallback('/[aeiouy]/', $replacer)->toArray());
