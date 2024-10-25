<?php

declare(strict_types=1);

namespace candidate_test\wrapper\array;

use test\Concerto\ConcertoTestCase;
use candidate\wrapper\array\{
    ReferToFunction,
    StandardArrayObject,
    ValueToFunction,
};

class StandardArrayObjectTest extends ConcertoTestCase
{
    public function toArrayProvider()
    {
        $array1 = range('A', 'Z');

        return [
            [
                $array1,
                $array1,
            ],
            [
                new \ArrayObject($array1),
                $array1,
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider toArrayProvider
    */
    public function toArray(
        $dataset,
        $expect,
    ) {
//      $this->markTestIncomplete();

        $obj = new StandardArrayObject($dataset);
        $this->assertEquals(
            $expect,
            $obj->toArray(),
        );
    }

    public function studyToSnaKeProvider()
    {
        return [
            [
                'studyString',
                'study_string',
            ],
            [
                'study',
                'study',
            ],
        ];
    }
    /**
    *   @test
    *   @dataProvider studyToSnaKeProvider
    */
    public function studyToSnaKe(
        $study_string,
        $expect,
    ) {
     // $this->markTestIncomplete();

        $obj = new StandardArrayObject([]);
        $this->assertEquals(
            $expect,
            $this->callPrivateMethod(
                $obj,
                'studyToSnaKe',
                [$study_string],
            ),
        );
    }

    /**
    *   @test
    */
    public function delegate()
    {
//      $this->markTestIncomplete();

        $obj = new StandardArrayObject([]);
        $this->callPrivateMethod(
            $obj,
            'delegate',
            [],
        );

        $functions = $this->getPrivateProperty($obj, 'functions');

        $expect = array_map(
            fn($name) => new $name(),
            $functions,
        );

        $this->assertEquals(
            $expect,
            $this->getPrivateProperty($obj, 'delegates'),
        );
    }

    public function hasInDeligateProvider()
    {
        return [
            //not have 'array_' prefix
            [
                'count',
                true,
            ],
            //have 'array_' prefix
            [
                'array_keys',
                true,
            ],
            //delete 'array_' prefix
            [
                'keys',
                false,
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider hasInDeligateProvider
    */
    public function hasInDeligate(
        $function_name,
        $expect,
    ) {
     // $this->markTestIncomplete();

        $obj = new StandardArrayObject([]);
        $this->assertEquals(
            $expect,
            $this->callPrivateMethod(
                $obj,
                'hasInDeligate',
                [$function_name],
            ),
        );
    }

    public function resolveDeligateProvider()
    {
        return [
            [
                'array_keys',
                ValueToFunction::class,
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider resolveDeligateProvider
    */
    public function resolveDeligate(
        $function_name,
        $expect,
    ) {
     // $this->markTestIncomplete();

        $obj = new StandardArrayObject([]);

        $actual = $this->callPrivateMethod(
            $obj,
            'resolveDeligate',
            [$function_name],
        );

        $this->assertEquals(
            $actual instanceof $expect,
            true,
        );
    }

    public function commomFunction1(
        $function_name,
        $dataset,
        $arguments,
        $expect,
    ) {
        $obj = new StandardArrayObject($dataset);
        $this->assertEquals(
            $expect,
            call_user_func_array(
                [$obj, $function_name],
                $arguments
            ),
        );
    }

    public function definedMethodProvider()
    {
        $array1 = range('A', 'Z');
        $array2 = range('a', 'z');

        return [
            [
                'combineKeyUseKey',
                $array1,
                [$array2],
                (new StandardArrayObject(
                    array_combine(
                        $array2,
                        array_keys($array1),
                    ),
                )),
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider definedMethodProvider
    */
    public function definedMethod(
        $function_name,
        $dataset,
        $arguments,
        $expect,
    ) {
      // $this->markTestIncomplete();

        $this->commomFunction1(
            $function_name,
            $dataset,
            $arguments,
            $expect,
        );
    }

    public function basicFunctionMethodProvider()
    {
        $array1 = range('A', 'Z');
        $array2 = range(1, 10, 1);

        return [
            //ValueToFunction & adds prefix & no args
            [
                'keys',
                $array1,
                [],
                new StandardArrayObject(range(0, 25, 1)),
            ],
            //ValueToFunction & adds prefix
            [
                'chunk',
                $array2,
                [
                    6,
                ],
                new StandardArrayObject([
                    range(1, 6, 1),
                    range(7, 10, 1),
                ]),
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider basicFunctionMethodProvider
    */
    public function basicFunctionMethod(
        $function_name,
        $dataset,
        $arguments,
        $expect,
    ) {
      // $this->markTestIncomplete();

        $this->commomFunction1(
            $function_name,
            $dataset,
            $arguments,
            $expect,
        );
    }

    public function relatedValueProvider()
    {
        $array1 = range('A', 'Z');
        $array2 = range(1, 10, 1);

        return [
            //ReferToFunction
            [
                'pop',
                $array1,
                [],
                'Z',
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider relatedValueProvider
    */
    public function relatedValue(
        $function_name,
        $dataset,
        $arguments,
        $expect,
    ) {
      // $this->markTestIncomplete();

        $obj = new StandardArrayObject($dataset);
        $actual_object = call_user_func_array(
            [$obj, $function_name],
            $arguments
        );

        $this->assertEquals(
            $expect,
            $actual_object->relatedValue(),
        );
    }
}
