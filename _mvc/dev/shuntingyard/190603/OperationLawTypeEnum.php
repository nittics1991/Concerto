<?php

//namespace dev\Valodator;

//use dev\accessor\Enum;

require_once 'Enum.php';

class OperationLawTypeEnum extends Enum
{
    /**
    *    law type
    *
    *    @var string
    */
    public const LEFT = 'left';
    public const RIGHT = 'right';
    public const NON = 'non';
}
