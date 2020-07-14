<?php

use GW\Value\StringValue;
use GW\Value\Wrap;

$array = Wrap::stringsArray(['John Black', 'Mary White', 'Jack Sparrow', 'Tia Dalma', 'Conchita Wurst']);

var_export(
    $array->toAssocValue()
        ->map(function (StringValue $person): string {
            return $person->toString();
        })
        ->mapKeys(function (int $oldKey, string $person): string {
            return $person;
        })
        ->toAssocArray()
);
