<?php

namespace Engine;

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
            // loop through the conditions
            foreach ($conditions as $condition) {
                if (count($condition) === 2) {
                    // two conditions
                    if ($request[$condition[0]] === $condition[1]) {
                        return true;
                    } else {
                        return false;
                    }
                } else if (count($condition) === 3) {
                    // three elements, middle is operator
                    switch ($condition[1]) {
                        default:
                        case '=':
                        case '==':  $result = $request[$condition[0]] ?? null == $condition[2]; break;
                        case '!=':
                        case '<>':  $result = $request[$condition[0]] ?? null != $condition[2]; break;
                        case '<':   $result = $request[$condition[0]] ?? null < $condition[2]; break;
                        case '>':   $result = $request[$condition[0]] ?? null > $condition[2]; break;
                        case '<=':  $result = $request[$condition[0]] ?? null <= $condition[2]; break;
                        case '>=':  $result = $request[$condition[0]] ?? null >= $condition[2]; break;
                        case '===': $result = $request[$condition[0]] ?? null === $condition[2]; break;
                        case '!==': $result = $request[$condition[0]] ?? null !== $condition[2]; break;
                    }
                    return $result;
                }
            }
        }

        return false;
    }

}
