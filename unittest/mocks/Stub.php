<?php

trait Stub
{
    static $calls = [];
    static $returns = [];

    public function stub($function, $args)
    {
        static::$calls[] = [$function => $args];

        if (isset(static::$returns[$function])) {
            return array_shift(static::$returns[$function]);
        }
    }

    public function __call($name, $args)
    {
        return $this->stub($name, $args);
    }
}