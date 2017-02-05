`AssocValue` defines interface of value object for associative array.

#### Examples

```php
<?php

$array = \GW\Value\Arrays::assoc(['a' => 1, 'b' => 2, 'c' => 3]);

$array['a']; // 1
$array[0]; // Exception
$array['d']; // Exception
$has = isset($array['a']); // true
$has = isset($array['d']); // false
$array->count(); // 3

foreach ($array as $key => $value) {
    echo "$key => $value ";
} // outputs: a => 1 b => 2 c => 3

```
