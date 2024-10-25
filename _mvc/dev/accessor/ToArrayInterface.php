<?php

/**
*   ToArrayInterface
*
*   @version 190520
*/

declare(strict_types=1);

namespace candidate\accessor;

interface ToArrayInterface
{
    /**
    *   配列へ変換
    *
    *   @return mixed[]
    */
    public function toArray();
}
