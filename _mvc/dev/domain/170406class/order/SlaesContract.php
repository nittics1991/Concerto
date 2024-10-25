<?php

/**
*   SlaesContract
*
*   @version 170308
*/

namespace dev\domain\contact;

use dev\domain\common\ValueObject;

class SlaesContract extends ValueObject
{
    /**
    *   @inheritDoc
    *
    */
    protected static $properties = ['denomination', 'factoringType', 'billSite'];

    public function isValidDenomination($val)
    {
        return Validate::isText($val);
    }

    public function isValidFactoringType($val)
    {
        return Validate::isText($val);
    }

    public function isValidBillSite($val)
    {
        if (!isset($val)) {
            return true;
        }
        return Validate::isFloat($val);
    }
}
