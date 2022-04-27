<?php

/**
*   SlaesPrice
*
*   @version 170308
*/

namespace dev\domain\contact;

use dev\domain\common\ValueObject;

class SlaesPrice extends ValueObject
{
    /**
    *   {inherit}
    *
    */
    protected static $properties = ['price', 'quantity', 'unit', 'unitPrice', 'taxRate'];

    /**
    *   getTaxInculusivePrice
    *
    *   @return int
    *   @throws InvalidArgumentException
    */
    public function getTaxInculusivePrice()
    {
        return floor($this->price * $this->taxRate / 100);
    }

    public function isValidPrice($val)
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

    public function isValidTaxRate($val)
    {
        return Validate::isInt($val);
    }
}
