<?php

namespace Engine\Fields;

class Select extends Field
{
    public $type = 'select';

    public array $options;

    /**
     * Set the available options for the field
     *
     * @param array $value
     * @return $this
     */
    public function options(array $options) : Select
    {
        $this->options = $options;

        return $this;
    }
}