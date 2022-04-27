<?php

/**
*   CuntomerId
*
*   @version 170308
*/

namespace dev\domain\customer;

use dev\domain\common\EntityObject;

class CuntomerId extends EntityObject
{
    /**
    *   {inherit}
    *
    */
    protected static $properties = ['id', 'name'];

    public function isValidId($val)
    {
        return Validate::isCustomer($val);
    }

    public function isValidName($val)
    {
        return Validate::isText($val, 0, 50);
    }
}
