<?php

/**
*   WorkBooks
*
*   @version WorkBooks220713
*/

declare(strict_types=1);

namespace dev\excel\wrapper;

use Countable;
use Generator;
use IteratorAggregate;
use dev\excel\wrapper\WorkBook;

class WorkBooks implements Countable,
    IteratorAggregate
{


    //あるいはApplicationに入れるか?


    /**
    *   @var array
    */
    protected array $workbooks = [];

    /**
    *   open
    *
    *   @param string $file_path
    *   @return WorkBook
    */
    public function open(
        string $file_path,
    ): WorkBook {
        
    }







    /**
    *   @inheritDoc
    *
    *   @return Generator
    */
    public getIterator(): Generator
    {
        foreach($this->workbooks as $workbook) {
            yield $workbook;
        }
    }

    /**
    *   @inheritDoc
    */
    public function count():int
    {
        return count($this->workbooks);
    }
}
