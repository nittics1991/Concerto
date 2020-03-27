<?php

/**
*   tpal0010
*
*   @version 190918
*/

declare(strict_types=1);

namespace Concerto\database;

use Concerto\standard\ModelData;
use Concerto\Validate;

class Tpal0010Data extends ModelData
{
    /**
    *   Columns
    *
    *   @var array
    */
    protected static $schema = [
        'chuban' => parent::STRING
        , 'sinki_kbn' => parent::STRING
        , 'u_name' => parent::STRING
        , 'setti_name' => parent::STRING
        , 'uriage_pday' => parent::STRING
        , 'uriage_day' => parent::STRING
        , 'kei_flg' => parent::STRING
        , 'up_day' => parent::STRING
        , 'total_net' => parent::INTEGER
        , 'd_hinmei' => parent::STRING
        , 'kisyu_cd' => parent::STRING
        , 'u_chu_no' => parent::STRING
        , 'm_ritu' => parent::INTEGER
        , 'tanto_cd' => parent::STRING
        , 'gb_cd' => parent::STRING
        , 'tanto' => parent::STRING
        , 'mitu_no' => parent::STRING
        , 'kaisyu_kei' => parent::STRING
        , 'kaisyu_kin' => parent::STRING
        , 'tegata' => parent::INTEGER
        , 'kari_kaku_kbn' => parent::STRING
        , 'simuketi' => parent::STRING
        , 'sp' => parent::INTEGER
        , 'approved_by2' => parent::STRING
        , 'c_up_day' => parent::STRING
        , 'juchu_day' => parent::STRING
        , 'u_sei_no' => parent::STRING
        , 'noki_pday' => parent::STRING
        , 'approved_date2' => parent::STRING
    ];
}
