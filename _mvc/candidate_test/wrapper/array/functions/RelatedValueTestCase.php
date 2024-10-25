<?php

declare(strict_types=1);

namespace candidate_test\wrapper\array\functions;

use LogicException;
use candidate_test\wrapper\array\functions\StandardArrayFunctionTestCase;
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
                print_r($result, true)
            );
        }

        $this->assertEquals(
            $expect,
            $result->relatedValue(),
        );
    }
}
