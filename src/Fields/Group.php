<?php

namespace Engine\Fields;

use Engine\Creatable;
use Engine\Fields\Concerns\HasVisibility;
use Illuminate\Support\Str;

class Group
{
    use Creatable, HasVisibility;

    public $name;

    public function __construct(
        public string $label,
        public array $fields = [],
        public array $extra = [], // in case you need a description or other data
    ) {
        $this->name = Str::snake($label);
    }
}