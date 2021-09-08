<?php

namespace Engine;

trait HasFields
{
    /**
     * Fields for the model to use in forms.
     *
     * @return array
     */
    public function fields() {
        return [];
    }

    public function keyedFields() {
        $fields = [];

        foreach ($this->fields() as $field) {
            $fields[$field->name] = $field;
        }

        return $fields;
    }
}