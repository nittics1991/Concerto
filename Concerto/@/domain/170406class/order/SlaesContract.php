<?php

/**
*   SlaesContract
*
*   @version 170308
*/

namespace Concerto\domain\contact;

use Concerto\domain\common\ValueObject;

class SlaesContract extends ValueObject
{
    /**
    *   {inherit}
    *
    **/
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
