<?php

declare(strict_types=1);

namespace Concerto\test\wrapper\array\functions;

use Concerto\test\wrapper\array\functions\StandardArrayFunctionTestCase;
use Concerto\wrapper\array\{
    StandardArrayObject,
};

class ShuffleFunctionTest extends StandardArrayFunctionTestCase
{
    protected string $function_name = 'shuffle';

    public function executeProvider()
    {
        $array1 = range(1, 10, 1);
        
        return [
            [
                $array1,
                [],
                $array1,
            ],
        ];
    }
    
    /**
    *   @test
    *   @dataProvider executeProvider
    */
    public function execute(
        $dataset,
        $arguments,
        $expect,
    ) {
      $this->markTestIncomplete();
        
        $obj = new StandardArrayObject($dataset);
        $result = call_user_func_array(
            [
                $obj,
                $this->function_name
            ],
            $arguments,
        );
        
        $result = is_object($result)?
            $result->toArray():$result;
        
        $this->assertSame(
            $expect,
            $result
        );
    }
}
