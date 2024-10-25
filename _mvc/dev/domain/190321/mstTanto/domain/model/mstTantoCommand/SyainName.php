<?php

use ValueObject;

class SyainName extends ValueObject
{
    public function __construct($id)
    {
        $id = mb_ereg_replace('\s', ' ', trim($id));
        parent::__construct($id);
    }
}
