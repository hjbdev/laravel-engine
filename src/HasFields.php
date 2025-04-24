<?php

namespace Engine;

trait HasFields
{
    /**
     * Fields for the model to use in forms.
     *
     * @return array
     */
    public function fields()
    {
        return [];
    }

    public function keyedFields()
    {
        $fields = [];

        foreach ($this->fields() as $field) {
            $fields[$field->name] = $field;
        }

        return $fields;
    }

    public function validationRules($action = 'create')
    {
        $rules = [];

        foreach ($this->fields() as $field) {
            $rules[$field->name] = $field->rules;

            if ($action === 'create' && is_array($field->creationRules)) {
                $rules[$field->name] = array_merge($rules[$field->name], $field->creationRules);
            }

            if ($action === 'update' && is_array($field->updateRules)) {
                $rules[$field->name] = array_merge($rules[$field->name], $field->updateRules);
            }
        }

        return $rules;
    }
}
