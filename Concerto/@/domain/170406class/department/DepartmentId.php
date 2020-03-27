<?php

/**
*   DepartmentId
*
*   @version 170314
*/

namespace Concerto\domain\department;

use Concerto\domain\common\EntityObject;

class DepartmentId extends EntityObject
{
    /**
    *   {inherit}
    *
    **/
    protected static $properties = ['id', 'name'];
    
    public function isValidId($val)
    {
        return Validate::isBumon($val);
    }
    
    public function isValidName($val)
    {
        return Validate::isText($val, 0, 20);
    }
}
