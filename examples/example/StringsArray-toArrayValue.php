<?php

use GW\Value\StringValue;
use GW\Value\Wrap;

$men = ['Jack', 'John'];
$women = ['Mary', 'Tia'];

$array = Wrap::stringsArray(['John Black', 'Mary White', 'Jack Sparrow', 'Tia Dalma', 'Conchita Wurst']);

var_export(
    $array->toArrayValue()
        ->map(function (StringValue $fullName) use ($women, $men): array {
            [$name, $surname] = explode(' ', $fullName->toString());
            $sex = in_array($name, $men, true) ? 'male' : (in_array($name, $women, true) ? 'female' : 'other');

            return ['name' => $name, 'surname' => $surname, 'sex' => $sex];
        })
        ->toArray()
);
