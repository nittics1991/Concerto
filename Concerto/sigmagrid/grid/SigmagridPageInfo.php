<?php

/**
*   Sigmagrid PageInfo
*
*   @version 170424
*/

declare(strict_types=1);

namespace Concerto\sigmagrid\grid;

use Concerto\standard\DataContainerValidatable;
use Concerto\Validate;

class SigmagridPageInfo extends DataContainerValidatable
{
    /**
    *   Columns
    *
    *   @var array
    */
    protected static $schema = array(
        'pageSize', 'pageNum', 'totalRowNum',
        'totalPageNum', 'startRowNum', 'endRowNum'
    );
    
    /**
    *   __construct
    *
    *   @param array $params
    **/
    public function __construct(array $params = [])
    {
        $this->fromArray($params);
    }
    
    public function isValidPageSize($val)
    {
        return Validate::isInt($val, 1);
    }
    
    public function isValidPageNum($val)
    {
        return Validate::isInt($val, 1);
    }
    
    public function isValidTotalPageNum($val)
    {
        return Validate::isInt($val, -1);
    }
    
    public function isValidStartPageNum($val)
    {
        return Validate::isInt($val, 0);
    }
    
    public function isValidEndPageNum($val)
    {
        return Validate::isInt($val, -1);
    }
}
