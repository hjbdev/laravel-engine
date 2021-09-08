<?php

namespace Engine\Fields;

use Closure;
use Engine\Creatable;
use Illuminate\Support\Str;

abstract class Field
{
    use Creatable;

    public $label;
    public $value;
    public $type;
    public $default;
    public $name;

    public $creationRules;
    public $updateRules;
    public $rules;


    public $visible = true;
    public $required = false;

    public function __construct($label)
    {
        $this->label = $label;
        $this->name = Str::snake($label);

        if (!$this->type) {
            throw new \Exception('This field does not have a type defined.');
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
    public function required($key = true, $operator = null, $value = null) : Field
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
    public function visible($key = true, $operator = null, $value = null) : Field
    {
        if (is_bool($key) || $key instanceof Closure) {
            $conditions = $key;
        } else {
            $conditions = [
                [...func_get_args()]
            ];
        }

        $this->visible = $conditions;

        return $this;
    }

    /**
     * Set the field name
     *
     * @param string $name
     * @return $this
     */
    public function name(string $name) : Field
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Set the default value for the field
     *
     * @param mixed $value
     * @return $this
     */
    public function default($value) : Field
    {
        $this->default = $value;

        return $this;
    }

    public function creationRules(...$rules) : Field
    {
        $this->creationRules = $rules;

        return $this;
    }

    public function updateRules(...$rules) : Field
    {
        $this->updateRules = $rules;

        return $this;
    }

    public function rules(...$rules) : Field
    {
        $this->rules = $rules;

        return $this;
    }
}
