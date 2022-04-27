<?php

declare(strict_types=1);

namespace test\Concerto\wrapper\array\functions;

use LogicException;
use test\Concerto\wrapper\array\functions\StandardArrayFunctionTestCase;
use candidate\wrapper\array\{
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
            $result->relatedValue(),
        );
    }
}
