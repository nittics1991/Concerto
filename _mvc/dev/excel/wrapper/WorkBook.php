<?php

/**
*   WorkBook
*
*   @version 220713
*/

declare(strict_types=1);

namespace dev\excel\wrapper;

use dev\excel\wrapper\WorkSheet;

class WorkBook
{
    /**
    *   @var array
    */
    protected array $worksheets = [];
    /**
    *   @var WorkSheet
    */
    protected WorkSheet $activeSheet;

    /**
    *   @inheritDoc
    */
    public function __destruct():void
    {
        //子objの整理
    }

    /**
    *   close
    *
    *   @return void
    */
    public function close():void
    {
        $this->__destruct();
    }

    /**
    *   worksheets
    *
    *   @param string $sheet_name
    *   @return WorkSheet
    */
    public function worksheets(
        string $sheet_name,
    ): WorkSheet {
        
    }

    /**
    *   save
    *
    *   @return WorkBook
    */
    public function save(): WorkBook
    {
        
        
        
        return $this;
    }

    /**
    *   saveAs
    *
    *   @param string $file_path
    *   @return WorkBook
    */
    public function saveAs(
        string $file_path,
    ): WorkBook {
        
        
        
        return $this;
    }

    /**
    *   saveCopyAs
    *
    *   @param string $file_path
    *   @return WorkBook
    */
    public function saveCopyAs(
        string $file_path,
    ): WorkBook {
        
        
        return new Workbook();
    }

    /**
    *   activate
    *
    *   @return WorkBook
    */
    public function activate(): WorkBook
    {
        
        
        return $this;
    }
    
    
    
    
}
