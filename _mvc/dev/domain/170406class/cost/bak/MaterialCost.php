<?php

/**
*   MaterialCost
*
*   @version 170316
*/

namespace dev\domain\contact;

use dev\domain\common\ValueObject;

class MaterialCost extends ValueObject
{
    /**
    *   {inherit}
    *
    */
    protected static $properties = ['procurementId'];

    /**
    *   {inherit}
    *
    */
    protected $delegates = ['manufacturingCost'];

    /**
    *   {inherit}
    *
    */
    public function __construct($param)
    {
        $this->boot();
        parent::__construct($param);
    }

    protected function boot()
    {
        $this->delegates['manufacturingCost'] = new ManufacturingCost();
    }

    public function isValidProcurementId()
    {
        return $this->procurementId->isValid();
    }
}
