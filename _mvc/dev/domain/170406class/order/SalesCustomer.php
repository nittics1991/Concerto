<?php

/**
*   SalesCustomer
*
*   @version 170308
*/

namespace dev\domain\order;

use dev\domain\common\EntityObject;
use dev\domain\customer\CustomerId;
use dev\domain\customer\CustomerOrderId;
use dev\domain\contact\PostalAddress;

class SalesCustomer extends EntityObject
{
    /**
    *   @inheritDoc
    *
    */
    protected static $properties = [
        'customerId',   //CustomerId
        'customerOrderId',  //CustomerOederId
        'receiver',
        'destinationAddress',   //PostalAddress
        'destinationTelephone', //TelephoneNumber
    ];

    /**
    *   @inheritDoc
    *
    */
    public function getId()
    {
        return $this->customerId->getId();
    }

    /**
    *   @inheritDoc
    *
    */
    public function equales()
    {
        return $this->customerId->equales();
    }

    public function isValidCustomerId($val)
    {
        if (!$val instanceof CustomerId) {
            return false;
        }
        return $this->customerId->isValid();
    }

    public function isValidCustomerOrderId($val)
    {
        if (!$val instanceof CustomerOrderId) {
            return false;
        }
        return $this->customerOrderId->isValid();
    }

    public function isValidReciever($val)
    {
        return Validate::isText($val, 0, 100);
    }

    public function isValidDestinationAddress($val)
    {
        if (!$val instanceof PostalAddress) {
            return false;
        }
        return $this->destinationAddress->isValid();
    }

    public function isValidDestinationTelephone($val)
    {
        if (!$val instanceof TelephoneNumber) {
            return false;
        }
        return $this->destinationTelephone->isValid();
    }
}
