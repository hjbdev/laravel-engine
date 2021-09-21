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
            $fields[$field->name] = $field->rules;

            if ($action === 'create' && is_array($field->creationRules)) {
                $fields[$field->name] = array_merge($fields[$field->name], $field->creationRules);
            }

            if ($action === 'update' && is_array($field->updateRules)) {
                $fields[$field->name] = array_merge($fields[$field->name], $field->updateRules);
            }
        }

        return $rules;
    }
}