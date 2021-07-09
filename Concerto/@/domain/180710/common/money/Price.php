<?php

/**
*   Price
*
*   @version 180810
*/

namespace Concerto\money;

use Concerto\math\UnitEnum;
use Concerto\money\Money;

class Price
{
    /**
    *   単価
    *
    *   @var Money
    */
    protected $unitPrice;

    /**
    *   数量
    *
    *   @var mixed
    */
    protected $quantity;

    /**
    *   単位
    *
    *   @var UnitEnum
    */
    protected $unit;

    /**
    *   価格
    *
    *   @var Money
    */
    protected $price;

    /**
    *   __construct
    *
    *   @param Money
    *   @param mixed
    *   @param UnitEnum
    *   @param Money
    */
    public function __construct(
        Money $unitPrice,
        $quantity,
        UnitEnum $unit,
        Money $price = null
    ) {
        $this->unitPrice = $unitPrice;
        $this->quantity = $quantity;
        $this->unit = $unit;
        $this->price = isset($price) ? $price : $unitPrice->mul($quantity);
    }
}
