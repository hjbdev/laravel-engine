<?php

namespace Engine;

use Engine\Fields\Group;
use Illuminate\Http\Request;

class Engine
{
    private string $model;

    public function __construct(string $model)
    {
        $this->model = $model;
    }

    public static function request(string $model, Request|array $request)
    {
        $instance = new static($model);

        return $instance->processRequest($request);
    }

    public function processRequest(Request|array $request)
    {
        // Get the request data
        if ($request instanceof Request) {
            $request = $request->all();
        }

        // Retrieve field definitions
        $model = $this->model;
        $fields = (new $model)->keyedFields();

        // Recursively process fields and groups
        $fields = $this->processFieldsOrGroups($fields, $request);

        return $fields;
    }

    protected function processFieldsOrGroups(array $fields, array $request)
    {
        foreach ($fields as $key => $field) {
            if ($field instanceof Group && isset($field->fields)) {
                // Recursively process group fields
                $field->fields = $this->processFieldsOrGroups($field->fields, $request);
                // Remove group if all its fields are hidden
                if (empty($field->fields)) {
                    unset($fields[$key]);
                }

                continue;
            }

            // Retrieve the field definition
            $conditions = $field->visible ?? true;
            $isVisible = $this->checkConditions($conditions, $request);

            if (! $isVisible) {
                unset($fields[$key]);
            }
        }

        return $fields;
    }

    protected function checkConditions($conditions, $request)
    {
        if (is_bool($conditions)) {
            // If it's a hardcoded boolean, return it.
            return $conditions;
        } elseif (is_callable($conditions)) {
            // If it's a closure, return it, given the full request.
            return $conditions($request);
        } elseif (is_array($conditions)) {
            // This should be an array of conditions, we need to do some checking here.
            // loop through the conditions
            foreach ($conditions as $condition) {
                if (count($condition) === 2) {
                    // two conditions
                    if ($request[$condition[0]] === $condition[1]) {
                        return true;
                    } else {
                        return false;
                    }
                } elseif (count($condition) === 3) {
                    // three elements, middle is operator
                    switch ($condition[1]) {
                        default:
                        case '=':
                        case '==':  $result = $request[$condition[0]] ?? $condition[2] == null;
                            break;
                        case '!=':
                        case '<>':  $result = $request[$condition[0]] ?? $condition[2] != null;
                            break;
                        case '<':   $result = $request[$condition[0]] ?? $condition[2] > null;
                            break;
                        case '>':   $result = $request[$condition[0]] ?? $condition[2] < null;
                            break;
                        case '<=':  $result = $request[$condition[0]] ?? $condition[2] >= null;
                            break;
                        case '>=':  $result = $request[$condition[0]] ?? $condition[2] <= null;
                            break;
                        case '===': $result = $request[$condition[0]] ?? $condition[2] === null;
                            break;
                        case '!==': $result = $request[$condition[0]] ?? $condition[2] !== null;
                            break;
                    }

                    return $result;
                }
            }
        }

        return false;
    }
}
