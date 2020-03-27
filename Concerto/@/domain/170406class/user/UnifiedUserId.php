<?php

/**
*   UnifiedUserId
*
*   @version 170314
*/

namespace Concerto\domain\user;

use Concerto\domain\common\EntityObject;

class UnifiedUserId extends EntityObject
{
    /**
    *   {inherit}
    *
    **/
    protected static $properties = ['id'];
    
    public function isValidId($val)
    {
        return Validate::isUser($val);
    }
}
