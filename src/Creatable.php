<?php

namespace Engine;

trait Creatable
{
    /**
     * Create a new instance
     *
     * @param  any  ...$arguments
     * @return static
     */
    public static function create(...$arguments)
    {
        return new static(...$arguments);
    }
}
