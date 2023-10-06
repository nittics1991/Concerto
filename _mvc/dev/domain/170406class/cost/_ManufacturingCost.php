<?php

/**
*   ManufacturingCost
*
*   @version 170308
*/

namespace dev\domain\cost;

class MaterialCost
class LarorCost
class OverheadCost
{
    $surrogateId;
    string $description;
    string $InfusionDestination;
    YearMonth $accountMonth;
    DateObject $costDate;
    int $quantity;
    int $unitPrice;
    string $unit;
    int $price;

    //materialのみ
    MaterialProcurementId $materialProcurementId

    OrderId $orderId;
    OrderDetailId $orderDetailId;
    DepartmentId $departmentId;
}
