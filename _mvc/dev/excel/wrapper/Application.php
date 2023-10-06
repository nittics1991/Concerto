<?php

/**
*   Application
*
*   @version 220713
*/

declare(strict_types=1);

namespace dev\excel\wrapper;

// use dev\excel\wrapper\WorkSheet;

class Application
{

    //WorkBooksと統合するか?


    /**
    *   @var bool
    */
    public bool $displayAlerts = false;

    /**
    *   @var Workbooks
    */
    public array $workbooks;

    /**
    *   @inheritDoc
    */
    public function __destruct():void
    {
        //子objの整理
    }

    /**
    *   quit
    *
    *   @return void
    */
    public function quit(): void
    {
        return $this->__destruct();
    }
}
