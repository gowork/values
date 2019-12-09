<?php

namespace GW\Value;

interface Stack
{
    /**
     * @param mixed $value
     * @return Stack
     */
    public function unshift($value): Stack;

    /**
     * @param mixed $value
     * @return Stack
     */
    public function shift(&$value = null): Stack;

    /**
     * @param mixed $value
     * @return Stack
     */
    public function push($value): Stack;

    /**
     * @param mixed $value
     * @return Stack
     */
    public function pop(&$value = null): Stack;
}
