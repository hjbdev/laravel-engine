<?php

use Engine\Engine;
use Engine\Fields\Text;
use Engine\Fields\Group;

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

test('engine handles groups and field visibility inside groups', function () {
    // Model with groups
    class GroupedModel {
        use \Engine\HasFields;
        public function fields() {
            return [
                Group::make('Personal', [
                    Text::create('First Name'),
                    Text::create('Last Name')->visible('show_last', '=', true),
                ]),
                Text::create('Email'),
            ];
        }
    }

    $model = 'GroupedModel';

    // Only 'First Name' should be visible in the group, plus 'Email'
    $request = ['show_last' => false];
    $fields = Engine::request($model, $request);
    expect($fields)->toHaveCount(2);
    expect($fields[0])->toBeInstanceOf(Group::class);
    expect($fields[0]->fields)->toHaveCount(1);
    expect($fields[0]->fields[0]->label)->toBe('First Name');
    expect($fields[1]->label)->toBe('Email');

    // Both fields in the group should be visible
    $request = ['show_last' => true];
    $fields = Engine::request($model, $request);
    expect($fields[0]->fields)->toHaveCount(2);
    expect($fields[0]->fields[1]->label)->toBe('Last Name');

    // If all fields in a group are hidden, the group should be removed
    class EmptyGroupModel {
        use \Engine\HasFields;
        public function fields() {
            return [
                Group::make('Hidden', [
                    Text::create('Hidden Field')->visible(false),
                ]),
                Text::create('Visible'),
            ];
        }
    }
    $model = 'EmptyGroupModel';
    $fields = Engine::request($model, []);
    expect($fields)->toHaveCount(1);
    expect($fields[0]->label)->toBe('Visible');
});