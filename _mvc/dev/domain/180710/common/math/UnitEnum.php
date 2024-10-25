<?php

/**
*   InvoiceUnitEnum
*
*   @version 180710
*/

namespace dev\Math;

use dev\standard\Enum;

class UnitEnum extends Enum
{
    /**
    *   単位
    *
    *   @var string
    */
    const BAG = 'bag';  //袋
    const BOLT = 'bolt';  //本
    const BOX = 'box';  //箱
    const BUNDLE = 'bundle';  //束
    const CASE = 'case';  //ケース
    const CM = 'cm';  //cm
    const DOZEN = 'dozen';  //ダース
    const G = 'g';  //g
    const KG = 'kg';  //kg
    const KL = 'kl';  //kL
    const L = 'l';  //リットル
    const M = 'm';  //m
    const PACK = 'pack';  //パッケージ
    const PAIR = 'pair';  //組
    const PIECE = 'piece';  //個
    const ROLL = 'roll';  //巻
    const SHEET = 'sheet';  //枚
    const SET = 'set';  //式
    const STAND = 'stand';  //台
    const T = 't';  //トン
}
