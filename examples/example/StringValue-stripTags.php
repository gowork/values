<?php

use GW\Value\Strings;

$html = Strings::create('<p>Html is <strong>cool</strong> but not always...</p>');

echo $html->stripTags()->toString();
