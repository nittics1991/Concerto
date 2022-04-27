<?php

declare(strict_types=1);

namespace test\Concerto\wrapper\array;

use test\Concerto\ConcertoTestCase;
use candidate\wrapper\array\BasicFunction;

class BasicFunctionTestObject1 extends BasicFunction
{
    protected array $functions = [
        __NAMESPACE__ . '\function1',
        __NAMESPACE__ . '\function2',
        __NAMESPACE__ . '\function3',
        __NAMESPACE__ . '\function4',
        __NAMESPACE__ . '\function5',
    ];

    protected array $not_first_array_argument = [
        __NAMESPACE__ . '\function2' => 1,
    ];

    protected array $has_related_value = [
        __NAMESPACE__ . '\function3',
    ];

    protected mixed $related_value = 'init_related_value';
}

function function1(...$arguments)
{
    return array_merge(['function1'], $arguments);
}

function function2(...$arguments)
{
    return array_merge(['function2'], $arguments);
}

function function3(...$arguments)
{
    return array_merge(['function3'], $arguments);
}

function function4(...$arguments)
{
    return false;
}

class BasicFunctionTest extends ConcertoTestCase
{
    /**
    *   @test
    */
    public function functionList()
    {
//      $this->markTestIncomplete();

        $obj = new BasicFunctionTestObject1();
        $expect = $this->getPrivateProperty(
            $obj,
            'functions'
        );
        $this->assertEquals(
            $expect,
            $obj->functionList(),
        );
    }

    public function hasProvider()
    {
        return [
            [
                __NAMESPACE__ . '\function1',
                true,
            ],
            [
                __NAMESPACE__ . '\function99',
                false,
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider hasProvider
    */
    public function has(
        $function_name,
        $expect
    ) {
//      $this->markTestIncomplete();

        $obj = new BasicFunctionTestObject1();
        $this->assertEquals(
            $expect,
            $obj->has($function_name),
        );
    }

    /**
    *   @test
    */
    public function relatedValue()
    {
//      $this->markTestIncomplete();

        $obj = new BasicFunctionTestObject1();
        $expect = $this->getPrivateProperty(
            $obj,
            'related_value'
        );
        $this->assertEquals(
            $expect,
            $this->callPrivateMethod(
                $obj,
                'relatedValue',
                [],
            ),
        );
    }

    /**
    *   @test
    *   @dataProvider hasProvider
    */
    public function checkFunction(
        $function_name,
        $expect
    ) {
//      $this->markTestIncomplete();

        $obj = new BasicFunctionTestObject1();

        try {
            $this->callPrivateMethod(
                $obj,
                'checkFunction',
                [$function_name],
            );
        } catch (\RuntimeException $e) {
            if ($expect) {
                $this->assertEquals(1, 0);
            } else {
                $this->assertEquals(1, 1);
            }
            return;
        }

        if (!$expect) {
            $this->assertEquals(1, 0);
        } else {
            $this->assertEquals(1, 1);
        }
    }

    public function resolveArgumentPositionProvider()
    {
        return [
            [
                __NAMESPACE__ . '\function2',
                1,
            ],
            [
                __NAMESPACE__ . '\function99',
                null,
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider resolveArgumentPositionProvider
    */
    public function resolveArgumentPosition(
        $function_name,
        $expect
    ) {
//      $this->markTestIncomplete();

        $obj = new BasicFunctionTestObject1();
        $this->assertEquals(
            $expect,
            $this->callPrivateMethod(
                $obj,
                'resolveArgumentPosition',
                [$function_name],
            ),
        );
    }

    public function resolveArgumentProvider()
    {
        return [
            [
                [1,2,3,4,5],
                __NAMESPACE__ . '\function1',
                [11,12,13],
                [[1,2,3,4,5],11,12,13],
            ],
            [
                [1,2,3,4,5],
                __NAMESPACE__ . '\function2',
                [11,12,13],
                [11,[1,2,3,4,5],12,13],
            ],
            [
                [1,2,3,4,5],
                __NAMESPACE__ . '\function1',
                [],
                [[1,2,3,4,5]],
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider resolveArgumentProvider
    */
    public function resolveArgument(
        $dataset,
        $function_name,
        $arguments,
        $expect
    ) {
//      $this->markTestIncomplete();

        $obj = new BasicFunctionTestObject1();
        $this->assertEquals(
            $expect,
            $this->callPrivateMethod(
                $obj,
                'resolveArgument',
                [
                    $dataset,
                    $function_name,
                    $arguments,
                ],
            ),
        );
    }

    /**
    *   @test
    */
    public function callFunctionNotCallableFailure()
    {
//      $this->markTestIncomplete();

        $obj = new BasicFunctionTestObject1();

        try {
            $this->callPrivateMethod(
                $obj,
                'callFunction',
                [
                    __NAMESPACE__ . '\function99',
                    []
                ],
            );
        } catch (\RuntimeException $e) {
            $this->assertEquals(1, 1);
            return;
        }
        $this->assertEquals(1, 0);
    }

    public function callFunctionProvider()
    {
        return [
            [
                __NAMESPACE__ . '\function1',
                [1,2,3],
                ['function1',1,2,3],
                'init_related_value',
            ],
            [
                __NAMESPACE__ . '\function3',
                [1,2,3],
                ['function3',1,2,3],
                ['function3',1,2,3],
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider callFunctionProvider
    */
    public function callFunction(
        $function_name,
        $arguments,
        $expect,
        $expect_related_value,
    ) {
//      $this->markTestIncomplete();

        $obj = new BasicFunctionTestObject1();

        $this->assertEquals(
            $expect,
            $this->callPrivateMethod(
                $obj,
                'callFunction',
                [$function_name, $arguments],
            ),
        );

        $this->assertEquals(
            $expect_related_value,
            $obj->relatedValue(),
        );
    }

    public function executeProvider()
    {
        return [
            [
                [1,2,3,4,5],
                __NAMESPACE__ . '\function1',
                [11,12,13],
                ['function1', [1,2,3,4,5],11,12,13],
                null,
            ],
            [
                [1,2,3,4,5],
                __NAMESPACE__ . '\function2',
                [11,12,13],
                ['function2', 11,[1,2,3,4,5],12,13],
                null,
            ],
            [
                [1,2,3,4,5],
                __NAMESPACE__ . '\function3',
                [11,12,13],
                ['function3', [1,2,3,4,5],11,12,13],
                ['function3', [1,2,3,4,5],11,12,13],
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider executeProvider
    */
    public function execute(
        $dataset,
        $function_name,
        $arguments,
        $expect,
        $expect_related_value,
    ) {
//      $this->markTestIncomplete();

        $obj = new BasicFunctionTestObject1();

        $this->assertEquals(
            $expect,
            $obj->execute(
                $dataset,
                $function_name,
                $arguments,
            ),
        );

        $this->assertEquals(
            $expect_related_value,
            $obj->relatedValue(),
        );
    }
}
