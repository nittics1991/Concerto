<?php

/**
*   SlaesCost
*
*   @version 170308
*/

namespace Concerto\domain\contact;

use Concerto\domain\common\ValueObject;

class SlaesCost extends ValueObject
{
    /**
    *   {inherit}
    *
    **/
    protected static $properties = ['cost', 'quantity', 'unit', 'unitPrice'];
    
    public function isValidCost($val)
    {
        return Validate::isInt($val);
    }
    
    public function isValidQuantity($val)
    {
        return Validate::isInt($val);
    }
    
    public function isValidUnit($val)
    {
        return Validate::isText($val);
    }
    
    public function isValidUnitPrice($val)
    {
        return Validate::isInt($val);
    }
}
