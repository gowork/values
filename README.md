# Values

## StringValue

```php
<?php

use GW\Value\Strings;

$str = Strings::create('test');
$str->length(); // 4
```

## ArrayValue

```php
<?php

use GW\Value\Arrays;

$arr = Arrays::create(['a', 'b', 'c']);
$arr->count(); // 3
$arr[1]; // b
$arr['c']; // Exception! (string offset)
$arr[3] = 'c'; // Exception! (immutable)
unset($arr[3]); // Exception! (immutable)
$hasThird = isset($arr[3]); // false

foreach ($arr as $v) {
    echo $v;
} // abc
```

## AssocArray

```php
<?php

use GW\Value\Arrays;

$arr = Arrays::assoc(['a' => 'A', 'b' => 'B', 'c' => 'C']);
$arr->count(); // 3
$arr->has('a'); // true
$arr['c']; // C
$hasB = isset($arr->keys()['b']); // true
$arr->keys()[0]; // a

$arr['D'] = 'x'; // Exception! (immutable)
unset($arr[3]); // Exception! (immutable)
$arr->with('d', 'D')['d']; // D
$arr->get('d'); // null
```
