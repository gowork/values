<?php

namespace GW\Value;

interface Stack
{
    /**
     * @param mixed $value
     * @return Stack
     */
    public function unshift($value);

    /**
     * @param mixed $value
     * @return Stack
     */
    public function shift(&$value = null);

    /**
     * @param mixed $value
     * @return Stack
     */
    public function push($value);

    /**
     * @param mixed $value
     * @return Stack
     */
    public function pop(&$value = null);
}
