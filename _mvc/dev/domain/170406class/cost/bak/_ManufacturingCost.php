<?php

/**
*   ManufacturingCost
*
*   @version 170308
*/

namespace dev\domain\order;

class Cost
{
    $surrigateKey
    $description
    DateObject $infusionDate;
    ManufacturingCostInterface $manufacturingCost
}

class MaterialCost extends ManufacturingCost
{
    ProcurementId $procurementId
}

class LarorCost extends ManufacturingCost
{
}

class OverheadCost extends ManufacturingCost
{
}

////////////////////////////////////////////////

class MaterialProcurementItemOrder
{
    MaterialProcurementId $MaterialProcurementId
    PuchasePrice $puchasePrice

    Supplier $Supplier
}

class MaterialProcurementId
{
    $id
}

class PurcharseId
{
    $id     //FE
    $name
}

class Supplier
{
    $id
    $name
}
