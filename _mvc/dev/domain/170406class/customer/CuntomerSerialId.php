<?php

/**
*   CuntomerSerialId
*
*   @version 170314
*/

namespace dev\domain\customer;

use dev\domain\common\EntityObject;

class CuntomerSerialId extends EntityObject
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
