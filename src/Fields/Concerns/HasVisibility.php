<?php

namespace Engine\Fields\Concerns;

use Closure;

trait HasVisibility
{
    public $visible = true;

    /**
     * Set whether the field is visible
     *
     * @param mixed $key
     * @param mixed $operator
     * @param mixed $value
     * @return $this
     */
    public function visible($key = true, $operator = null, $value = null): self
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
}
