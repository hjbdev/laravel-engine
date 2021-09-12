<?php

use Engine\Engine;
use Engine\Fields\Text;

class FakeModel {
    use \Engine\HasFields;
    public function fields() {
        return [
            Text::create('First Name'),
            Text::create('Last Name')->visible('first_name', '!=', null)
        ];
    }
}

test('engine validates a request successfully', function () {
    $model =  'FakeModel';

    $request = [
        'first_name' => null,
        'last_name' => null
    ];

    $fields = Engine::request($model, $request);

    expect($fields)->toHaveCount(1);

    $request['first_name'] = 'Hello';

    $fields = Engine::request($model, $request);

    expect($fields)->toHaveCount(2);
});