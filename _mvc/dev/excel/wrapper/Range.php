<?php

/**
*   Range
*
*   @version 220713
*/

declare(strict_types=1);

namespace dev\excel\wrapper;

use dev\excel\wrapper\{
    Border,
    Interior,
    Row,
};

class Range
{
    /**
    *   @var string|int|float
    */
    private $value;
    
    /**
    *   borders
    *
    *   @param int $type
    *   @return Border
    */
    public function borders(
        int $type,
    ): Border {
        
    }

    /**
    *   interior
    *
    *   @return Interior
    */
    public function interior(): Interior
    {
        
    }

    /**
    *   specialCells
    *
    *   @param int $type
    *   @return Range
    */
    public function specialCells(
        int type
    ): Range
    {
        
    }

    /**
    *   row
    *
    *   @return Row
    */
    public function row(): Row
    {
        
    }

    /**
    *   copy
    *
    *   @return Range
    */
    public function copy(): Range
    {
        
    }

    /**
    *   pasteSpecial
    *
    *   @param int $type
    *   @return Range
    */
    public function pasteSpecial(
        int $type,
    ): Range
    {
        
    }






}
