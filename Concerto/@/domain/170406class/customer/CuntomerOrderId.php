<?php

/**
*   CuntomerOrderId
*
*   @version 170309
*/

namespace Concerto\domain\customer;

use Concerto\domain\common\EntityObject;

class CuntomerOrderId extends EntityObject
{
    /**
    *   {inherit}
    *
    **/
    protected static $properties = ['id'];
    
    public function isValidId($val)
    {
        return Validate::isCustomerOrderId($val);
    }
}
