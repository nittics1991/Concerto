<?php

/**
*   EXCEL builder interface
*
*   @version 221212
*/

declare(strict_types=1);

namespace Concerto\excel\excel;

interface ExcelBuilderInterface
{
    /**
    *    build
    *
    *    @param mixed $excel
    *    @param mixed $book
    */
    public function build(
        $excel,
        $book
    );
}
