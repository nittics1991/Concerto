<?php

/**
*   mst_mondai_bunrui1
*
*   @version 200605
*/

declare(strict_types=1);

namespace Concerto\database;

use Concerto\standard\ModelData;
use Concerto\Validate;

class MstMondaiBunrui1Data extends ModelData
{
    /**
    *   Columns
    *
    *   @var array
    */
    protected static $schema = [
        'no_bunrui' => parent::INTEGER
        , 'nm_bunrui' => parent::STRING
    ];

    /**
    *   Column Alias
    *
    *   @var array
    */
    protected static $alias = [
    ];

    public function isValidNo_bunrui($val)
    {
        return Validate::isInt($val, 1);
    }

    public function isValidNm_bunrui($val)
    {
        return Validate::isText($val);
    }
}
