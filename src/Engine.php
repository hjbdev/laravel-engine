<?php

namespace Engine;

use Illuminate\Http\Request;

class Engine
{
    private object $model;

    public function __construct(object $model)
    {
        $this->model = $model;
    }

    public static function request(object $model, Request|array $request) 
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
        $fields = $this->model->keyedFields();

        foreach ($request as $key => $value) 
        {
            // Retrieve the field definition
            $field = $fields[$key] ?? false;
            // If we don't have a definition, continue the loop.
            if (!$field) continue;

            $conditions = $field->visible;

            $isVisible = $this->checkConditions($conditions, $request);

            if (!$isVisible) {
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
        } else if (is_callable($conditions)) {
            // If it's a closure, return it, given the full request.
            return $conditions($request);
        } else if (is_array($conditions)) {
            // This should be an array of conditions, we need to do some checking here.
            $vis = false;
            // loop through the conditions
            foreach ($conditions as $condition) {
                if (count($condition) === 2) {
                    // two conditions
                    if ($request[$condition[0]] === $condition[1]) {
                        $vis = true;
                    } else {
                        return false;
                    }
                } else if (count($condition) === 3) {
                    // three elements, middle is operator
                    $result = false;
                    switch ($condition[1]) {
                        default:
                        case '=':
                        case '==':  $result = $condition[0] == $condition[2]; break;
                        case '!=':
                        case '<>':  $result = $condition[0] != $condition[2]; break;
                        case '<':   $result = $condition[0] < $condition[2]; break;
                        case '>':   $result = $condition[0] > $condition[2]; break;
                        case '<=':  $result = $condition[0] <= $condition[2]; break;
                        case '>=':  $result = $condition[0] >= $condition[2]; break;
                        case '===': $result = $condition[0] === $condition[2]; break;
                        case '!==': $result = $condition[0] !== $condition[2]; break;
                    }
                    // If it's true, continue looping.
                    if(!$result) return false;
                }
            }
        }

        return false;
    }

}
