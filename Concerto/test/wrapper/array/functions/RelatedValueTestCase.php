<?php

declare(strict_types=1);

namespace Concerto\test\wrapper\array\functions;

use LogicException;
use Concerto\test\wrapper\array\functions\StandardArrayFunctionTestCase;
use Concerto\wrapper\array\{
    StandardArrayObject,
};

abstract class RelatedValueTestCase extends StandardArrayFunctionTestCase
{
    abstract public function relatedValueProvider();
    
    /**
    *   @test
    *   @dataProvider relatedValueProvider
    */
    public function relatedValue(
        $dataset,
        $arguments,
        $expect,
    ) {
      // $this->markTestIncomplete();
        
        $obj = new StandardArrayObject($dataset);
        $result = call_user_func_array(
            [
                $obj,
                $this->function_name
            ],
            $arguments
        );
        
        if (!($result instanceof StandardArrayObject)) {
            throw new LogicException(
                "result must be StandardArrayObject:" .
                var_export($result, true)
            );
        }
        
        $this->assertEquals(
            $expect,
            $obj->relatedValue(),
        );
    }
}
