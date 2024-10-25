<?php

/**
*   TelephonNumber
*
*   @version 170308
*/

namespace dev\domain\contact;

use dev\domain\common\ValueObject;

class TelephonNumber extends ValueObject
{
    /**
    *   @inheritDoc
    *
    */
    protected static $properties = ['no'];

    public function isValidNo($val)
    {
        return Validate::isTel($val);
    }
}
