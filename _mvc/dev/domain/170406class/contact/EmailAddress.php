<?php

/**
*   EmailAddress
*
*   @version 170321
*/

namespace dev\domain\contact;

use dev\domain\common\ValueObject;

class EmailAddress extends ValueObject
{
    /**
    *   {inherit}
    *
    */
    protected static $properties = ['address', 'name'];

    /**
    *   construct
    *
    *   @param string
    */
    public function __construct($param)
    {
        $this->fromArray($param);
    }

    public function isValidAddress($val)
    {
        return Validate::isEmail($val);
    }
}
