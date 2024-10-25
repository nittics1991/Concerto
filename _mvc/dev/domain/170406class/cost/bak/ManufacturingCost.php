<?php

/**
*   ManufacturingCost
*
*   @version 170314
*/

namespace dev\domain\contact;

use dev\domain\common\ValueObject;

class ManufacturingCost extends ValueObject
{
    /**
    *   @inheritDoc
    *
    */
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
        return Validate::isUnit($val);
    }

    public function isValidUnitPrice($val)
    {
        return Validate::isInt($val);
    }
}
