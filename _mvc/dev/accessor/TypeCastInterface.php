<?php

/**
*   TypeCastInterface
*
*   @version 190516
*/

declare(strict_types=1);

namespace candidate\accessor;

interface TypeCastInterface
{
    /**
    *   set cast定義存在確認|定義取得
    *
    *   @param ?string $name
    *   @return bool|mixed[]
    */
    public function hasSetCastType(?string $name = null);

    /**
    *   get cast定義存在確認|定義取得
    *
    *   @param ?string $name
    *   @return bool|mixed[]
    */
    public function hasGetCastType(?string $name = null);
}
