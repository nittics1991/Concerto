<?php

use ValueObject;

class EmailAddress extends ValueObject
{
    public function user()
    {
        $exploded = explode('@', $this->id);
        return array_first($exploded);
    }

    public function domain()
    {
        $exploded = explode('@', $this->id);
        return array_last($exploded);
    }
}
