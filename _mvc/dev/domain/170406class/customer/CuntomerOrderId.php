<?php

/**
*   CuntomerOrderId
*
*   @version 170309
*/

namespace dev\domain\customer;

use dev\domain\common\EntityObject;

class CuntomerOrderId extends EntityObject
{
    /**
    *   @inheritDoc
    *
    */
    protected static $properties = ['id'];

    public function isValidId($val)
    {
        return Validate::isCustomerOrderId($val);
    }
}
