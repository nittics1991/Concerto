<?php

/**
*   TelephonNumber
*
*   @version 170308
*/

namespace Concerto\domain\contact;

use Concerto\domain\common\ValueObject;

class TelephonNumber extends ValueObject
{
    /**
    *   {inherit}
    *
    */
    protected static $properties = ['no'];

    public function isValidNo($val)
    {
        return Validate::isTel($val);
    }
}
