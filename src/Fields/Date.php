<?php

namespace Engine\Fields;

use Illuminate\Support\Carbon;

class Date extends Field
{
    public $type = 'date';

    /**
     * Set the earliest date allowed. Must be in Y-m-d format.
     *
     * @return $this
     */
    public function min(string|Carbon $min)
    {
        if (is_string($min)) {
            $this->min = $min;
        } else {
            $this->min = $min->format('Y-m-d');
        }

        return $this;
    }

    /**
     * Set the latest date allowed. Must be in Y-m-d format.
     *
     * @param  int|float  $max
     * @return $this
     */
    public function max(string|Carbon $max)
    {
        if (is_string($max)) {
            $this->max = $max;
        } else {
            $this->max = $max->format('Y-m-d');
        }

        return $this;
    }
}
