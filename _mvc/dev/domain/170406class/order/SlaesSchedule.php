<?php

/**
*   SlaesSchedule
*
*   @version 170308
*/

namespace dev\domain\contact;

use dev\domain\common\ValueObject;
use dev\datetime\DateTimeObject;
use dev\datetime\YearMonth;

class SlaesSchedule extends ValueObject
{
    /**
    *   @inheritDoc
    *
    */
    protected static $properties = [
        'deliveryDate',
        'paymentDate',
        'orderIssueDate',
        'orderDate',
        'orderNumberingIsssueDate',
        'salesDate',
        'quotationSalesDate',
    ];

    public function isValidDeliveryDate($val)
    {
        return ($val instanceof DateTimeObject);
    }

    public function isValidPaymentDate($val)
    {
        return ($val instanceof DateTimeObject);
    }

    public function isValidOrderIssueDate($val)
    {
        return ($val instanceof DateTimeObject);
    }

    public function isValidOrderDate($val)
    {
        return ($val instanceof DateTimeObject);
    }

    public function isValidOrderNumberingIsssueDate($val)
    {
        return ($val instanceof DateTimeObject);
    }

    public function isValidSalesDate($val)
    {
        return ($val instanceof DateTimeObject);
    }

    public function isValidQuotationSalesDate($val)
    {
        return ($val instanceof YearMonth);
    }
}
