<?php

class TestField extends \Engine\Fields\Field
{
    public $type = 'test';
}

test('field sets validation rules', function () {
    $field = TestField::create('First Name')->rules('string', 'max:40');
    expect($field->rules)->toBe(['string', 'max:40']);
});

test('field sets creation validation rules', function () {
    $field = TestField::create('First Name')->creationRules('string', 'max:40');
    expect($field->creationRules)->toBe(['string', 'max:40']);
});

test('field sets update validation rules', function () {
    $field = TestField::create('First Name')->updateRules('string', 'max:40');
    expect($field->updateRules)->toBe(['string', 'max:40']);
});

test('field sets label', function () {
    $field = TestField::create('First Name');
    expect($field->label)->toBe('First Name');
});

test('default field value can be set', function () {
    $field = TestField::create('First Name')->default('Harry');
    expect($field->default)->toBe('Harry');
});

test('field sets visibility conditions with boolean', function () {
    $field = TestField::create('First Name')->visible(true);
    expect($field->visible)->toBe(true);
});

test('field sets visibility conditions with closure', function () {
    $field = TestField::create('First Name')->visible(fn ($value) => $value === 'test');
    $closure = $field->visible;
    expect($closure('test'))->toBe(true);
    expect($closure('test!'))->toBe(false);
});

test('field sets visibility conditions with three params', function () {
    $field = TestField::create('First Name')->visible('first_name', '=', 'harry');
    expect($field->visible)->toBe([['first_name', '=', 'harry']]);
});

test('field sets required', function () {
    $field = TestField::create('First Name')->required();
    $optionalField = TestField::create('Last Name')->required(false);
    expect($field->required)->toBeTrue();
    expect($optionalField->required)->toBeFalse();
});

test('field sets name based on label when unspecified', function () {
    $field = TestField::create('First Name');
    expect($field->name)->toBe('first_name');
});

test('field sets name based on dev input when specified', function () {
    $field = TestField::create('First Name')->name('last_name');
    expect($field->name)->toBe('last_name');
});

test('select field has options', function () {
    $field = \Engine\Fields\Select::create('User Type')->options([
        'admin' => 'Admin',
        'moderator' => 'Moderator',
        'regular' => 'Regular'
    ]);

    expect($field->options)->toBe([
        'admin' => 'Admin',
        'moderator' => 'Moderator',
        'regular' => 'Regular'
    ]);
});
