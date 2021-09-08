<?php

test('has fields trait adds fields method', function () {
    $mock = mock(\Engine\HasFields::class);
    expect($mock->fields())
        ->toBeArray()
        ->toBeEmpty();
});