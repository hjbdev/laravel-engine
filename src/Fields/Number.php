<?php

namespace Engine\Fields;

class Number extends Field
{
    public $type = 'number';

    public $min;

    public $max;

    public $step;

    /**
     * Set the minimum value allowed
     *
     * @return $this
     */
    public function min(int|float $min)
    {
        $this->min = $min;

        return $this;
    }

    /**
     * Set the maximum value allowed
     *
     * @return $this
     */
    public function max(int|float $max)
    {
        $this->max = $max;

        return $this;
    }

    /**
     * Set the step value
     *
     * @return $this
     */
    public function step(int|float $step)
    {
        $this->step = $step;

        return $this;
    }
}
