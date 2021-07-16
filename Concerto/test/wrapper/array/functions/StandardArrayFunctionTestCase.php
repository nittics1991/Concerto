<?php

declare(strict_types=1);

namespace Concerto\test\wrapper\array\functions;

use Concerto\test\ConcertoTestCase;
use Concerto\wrapper\array\{
    StandardArrayObject,
};

abstract class StandardArrayFunctionTestCase extends ConcertoTestCase
{
    protected string $function_name = '';

    public function commomFunction1(
        $dataset,
        $arguments,
        $expect,
    ) {
        $obj = new StandardArrayObject($dataset);
        $result = call_user_func_array(
            [
                $obj,
                $this->function_name
            ],
            $arguments
        );
        
        $this->assertEquals(
            $expect,
            is_object($result)? $result->toArray():$result,
            var_export($result, true),
        );
    }

    abstract public function executeProvider();
    
    /**
    *   @test
    *   @dataProvider executeProvider
    */
    public function execute(
        $dataset,
        $arguments,
        $expect,
    ) {
      // $this->markTestIncomplete();
        
        $this->commomFunction1(
            $dataset,
            $arguments,
            $expect,
        );
    }
}
