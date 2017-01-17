<?php

namespace GW\Value;

interface StringsValue extends CharsValue, ArrayValue
{
    /**
     * @return StringValue
     */
    public function implode(string $glue);
}
