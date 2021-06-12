<?php

namespace Former\Fields;

use Closure;
use Former\Creatable;

abstract class Field 
{
    use Creatable;
    
    public $label;
    public $value;
    public $type;
    public $default;

    public $creationRules;
    public $updateRules;
    public $rules;

    
    public $visible = true;
    public $required = false;

    public function __construct($label)
    {
        $this->label = $label;

        if (!$this->type) {
            throw new \Exception ('This field does not have a type defined.');
        }
    }

    /**
     * Set whether the field is required
     *
     * @param mixed $key
     * @param mixed $operator
     * @param mixed $value
     * @return $this
     */
    public function required($key = true, $operator = null, $value = null)
    {
        if (is_bool($key)) {
            $conditions = $key;
        } else if ($key instanceof Closure) {
            $conditions = $key();
        } else {
            $conditions = [
                [...func_get_args()]
            ];
        }

        $this->required = $conditions;

        return $this;
    }

    /**
     * Set whether the field is visible
     *
     * @param mixed $key
     * @param mixed $operator
     * @param mixed $value
     * @return $this
     */
    public function visible($key = true, $operator = null, $value = null)
    {
        if (is_bool($key)) {
            $conditions = $key;
        } else if ($key instanceof Closure) {
            $conditions = $key();
        } else {
            $conditions = [
                [...func_get_args()]
            ];
        }

        $this->visible = $conditions;

        return $this;
    }

    /**
     * Set the default value for the field
     *
     * @param mixed $value
     * @return void
     */
    public function default($value)
    {
        $this->default = $value;

        return $this;
    }

    public function creationRules(...$rules)
    {
        $this->creationRules = $rules;
        
        return $this;
    }

    public function updateRules(...$rules)
    {
        $this->updateRules = $rules;

        return $this;
    }

    public function rules(...$rules)
    {
        $this->rules = $rules;

        return $this;
    }

    
}