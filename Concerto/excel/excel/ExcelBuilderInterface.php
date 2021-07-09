<?php

/**
*   EXCEL builder interface
*
*   @version 150419
*/

declare(strict_types=1);

namespace Concerto\excel\excel;

interface ExcelBuilderInterface
{
    /**
    *    データ作成
    *
    *    @param resource $excel EXCEL APP
    *    @param resource $book EXCEL BOOK
    *    @return bool
    */
    public function build($excel, $book);
}
