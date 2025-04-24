<?php

namespace Engine\Fields;

use Closure;
use Engine\Creatable;
use Engine\Fields\Concerns\HasVisibility;
use Illuminate\Support\Str;

abstract class Field
{
    use Creatable, HasVisibility;

    public $label;

    public $value;

    public $type;

    public $default;

    public $name;

    public $creationRules;

    public $updateRules;

    public $rules;

    public $required = false;

    public function __construct($label)
    {
        $this->label = $label;
        $this->name = Str::snake($label);

        if (! $this->type) {
            throw new \Exception('This field does not have a type defined.');
        }
    }

    /**
     * Set whether the field is required
     *
     * @param  mixed  $key
     * @param  mixed  $operator
     * @param  mixed  $value
     * @return $this
     */
    public function required($key = true, $operator = null, $value = null): static
    {
        if (is_bool($key)) {
            $conditions = $key;
        } elseif ($key instanceof Closure) {
            $conditions = $key();
        } else {
            $conditions = [
                [...func_get_args()],
            ];
        }

        $this->required = $conditions;

        return $this;
    }

    /**
     * Set the field name
     *
     * @return $this
     */
    public function name(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Set the default value for the field
     *
     * @param  mixed  $value
     * @return $this
     */
    public function default($value): static
    {
        $this->default = $value;

        return $this;
    }

    /**
     * Set the create validation rules for the field
     *
     * @param  mixed  $rules
     * @return $this
     */
    public function creationRules(...$rules): static
    {
        $this->creationRules = $rules;

        return $this;
    }

    /**
     * Set the update validation rules for the field
     *
     * @param  mixed  $rules
     * @return $this
     */
    public function updateRules(...$rules): static
    {
        $this->updateRules = $rules;

        return $this;
    }

    /**
     * Set validation rules for the field
     *
     * @param  mixed  $rules
     * @return $this
     */
    public function rules(...$rules): static
    {
        $this->rules = $rules;

        return $this;
    }
}
