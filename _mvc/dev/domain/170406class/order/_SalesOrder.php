<?php

/**
*   SalesOrder
*
*   @version 170308
*/

namespace dev\domain\order;

class SalesOrder
{
    OrderId $orderId;
    SalesCustomer $salesCustomer;   //関連が外部.customerはorder外なのでIDのみ？
    DepartmentId $salesDepartment;  //関連が外部
    QuatantionId $quatantionId  //関連が外部
    SalesPrice $salesPrice;
    SalesContract $salesContract;
    SalesSchadule $salesSchadule;
    ModelCodeId $modelCodeId;   //関連が外部
    ApprovalUsers $approvalUsers    //関連が外部?

    $provisionalResistration    //仮登録
    $oederDetailes = [];





    public function getGrossMargin()
    {
        $total = 0;
        foreach ($this->oederDetailes as $detail) {
        }
        return $total;
    }

    public function getGrossMarginRate()
    {
        return round($this->SalesPlice->price / $this->getGrossMargin() * 100, 2);
    }


    public function isValidOederId($val)
    {
    }
}
