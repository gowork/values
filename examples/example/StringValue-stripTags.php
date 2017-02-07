<?php

use GW\Value\Wrap;

$html = Wrap::string('<p>Html is <strong>cool</strong> but not always...</p>');

echo $html->stripTags()->toString();
