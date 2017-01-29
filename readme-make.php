<?php

use doc\GW\Value\ReadmeWriter;
use GW\Value\ArrayValue;
use GW\Value\AssocValue;
use GW\Value\StringsValue;
use GW\Value\StringValue;

require 'vendor/autoload.php';

$markdown = (new ReadmeWriter())->describeClasses([
    ArrayValue::class,
    AssocValue::class,
    StringValue::class,
    StringsValue::class,
]);

file_put_contents('README.md', $markdown->toString());
