<?php

namespace Engine\Fields;

class File extends Field
{
    public $type = 'date-time';

    public $limit = null;

    /**
     * Set the limit of how many files are allowed.
     *
     * @param bool $value
     * @return $this
     */
    public function limit($limit = null)
    {
        $this->limit = $limit;

        return $this;
    }
}
