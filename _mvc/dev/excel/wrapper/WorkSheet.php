<?php

/**
*   WorkSheet
*
*   @version 220713
*/

declare(strict_types=1);

namespace dev\excel\wrapper;

use dev\excel\wrapper\{
    Cell,
    Range,
};

class WorkSheet
{
    /**
    *   @var string
    */
    protected string $name;

    /**
    *   @var array
    */
    protected array $ranges;

    /**
    *   range
    *
    *   $sheet->range(cells,cells)の処理をどうする?
    *
    *   @param string|int|Cell $row_range A1:B3 'RANGE_NAME' (cells,cells)
    *   @return Range
    */
    public function range(
        string $range_name,
    ): Range {
        
    }

    /**
    *   rows
    *
    *   @param string|int $row_range 2:3 3
    *   @return Range
    */
    public function rows(
        string $row_range,
    ): Range {
        
    }

    /**
    *   cells
    *
    *   @param string $range_name
    *   @return Cell
    */
    public function cells(
        int $row,
        int $column,
    ): Cell {
        
    }

    /**
    *   shapes
    *
    *   @return Shape
    */
    public function rows(): Shape {
        
    }

    /**
    *   usedRange
    *
    *   @return Range
    */
    public function usedRange():Range
    {
        //微妙だが...
        
    }

    /**
    *   delete
    *
    *   @return void
    */
    public function delete():void
    {
    }

    /**
    *   activate
    *
    *   @return WorkSheet
    */
    public function activate():WorkSheet
    {
    }







    /**
    *   protect
    *
    *
    *
    *
    *   @return WorkSheet
    */
    public function protect(
        string $password,
        bool $param1,
        ????|null $param1,
        bool $param1,
        bool $param1,
    ): WorkSheet
    {
        //どうする？
    }


    /**
    *   queryTables
    *
    *   @return Page
    */
    public function pageSetup(): Page
    {
        //どうする？
        
        $sheet->pageSetup->PrintArea = Range
        
        
    }




    /**
    *   queryTables
    *
    *   @return QueryTable
    */
    public function queryTable(): QueryTable
    {
        //どうする？
        //BuilderADOのみ
        
        /*
        QueryTable
            add()
            name
            reflashStyle
            refleshPeriod
            textFilePlatform
            textFileStartRow
            textFileCommaDelimiter
            adjustColumnWidth
            reflesh()
            delete()
        */
    }






}
