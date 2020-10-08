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
     * @return Stack
     */
    public function shift(): Stack;

    /**
     * @param mixed $value
     * @return Stack
     */
    public function push($value): Stack;

    /**
     * @return Stack
     */
    public function pop(): Stack;
}
