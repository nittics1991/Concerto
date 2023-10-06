<?php

declare(strict_types=1);

namespace tool_test;

use test\Concerto\ConcertoTestCase;
use tool\TypeHintChecker;
use PhpToken;
use ReflectionClass;
use Throwable;
use tool_test\stubs\{
    SinglePhpClass,
    MultiNamespacePhpClass,
};

class TypeHintCheckerTest extends ConcertoTestCase
{
    protected function makeStubFilePath(
        string $fileName,
    ): string {
        return implode(
            DIRECTORY_SEPARATOR,
            [
                __DIR__,
                'stubs',
                $fileName,
            ],
        ) . '.php';
    }

    /**
    *   @test
    */
    public function readFile1()
    {
//      $this->markTestIncomplete();

        $obj = new TypeHintChecker();

        try {
            $contents = $this->callPrivateMethod(
                $obj,
                'readFile',
                [$this->makeStubFilePath('SinglePhpClass')],
            );

            $this->assertEquals(1, 1);
        } catch (Throwable $e) {
            $this->assertEquals(1, 0);
        }

        $this->assertGreaterThan(
            0,
            strlen($contents),
        );
    }

    /**
    *   @test
    */
    public function lexicalAnalysis()
    {
//      $this->markTestIncomplete();

        $obj = new TypeHintChecker();

        $contents = $this->callPrivateMethod(
            $obj,
            'readFile',
            [$this->makeStubFilePath('SinglePhpClass')],
        );

        $this->callPrivateMethod(
            $obj,
            'lexicalAnalysis',
            [$contents],
        );

        $this->assertGreaterThan(
            0,
            $obj->phpTokens,
        );
    }

    public function phpCodeTokens()
    {
        //no class 2 namespace
        $tokens[0] = PhpToken::tokenize(
            '<?php
                /*dummy*/

                declare(strict_types=1);

                namespace dummy\\tool;
                
                echo "aaaa";

                namespace dummy\\tool2;
                
                echo "bbb";
            '
        );

        //
        $tokens[1] = PhpToken::tokenize(
            '<?php
                /*dummy2*/

                declare(strict_types=1);

                namespace dummy\\tool;
                
                class Dummy1 extends stdClass implements
                    Countable
                {
                    private int $prop = 1;
                    
                    private function method(
                        int $arg,
                    ):void {
                        $this->prop = $arg;
                    }
                    
                    public function count():int
                    {
                        return 1;
                    }
                }
            '
        );

        return $tokens;
    }

    public function getNamespaceProvider()
    {
        $tokens = $this->phpCodeTokens();

        return [
            [
                $tokens[0],
                12,
                'dummy\\tool',
            ],
            [
                $tokens[0],
                22,
                'dummy\\tool2',
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider getNamespaceProvider
    */
    public function getNamespace(
        $tokens,
        $position,
        $expect,
    ) {
//      $this->markTestIncomplete();

        $obj = new TypeHintChecker();

        $obj->phpTokens = $tokens;

        $newPosition = $this->callPrivateMethod(
            $obj,
            'getNamespace',
            [$position],
        );

        $this->assertGreaterThan(
            $position,
            $newPosition,
        );

        $actual = $this->getPrivateProperty(
            $obj,
            'currentNamespace',
        );

        $this->assertEquals(
            $expect,
            $actual,
        );
    }

    public function isClassProvider()
    {
        return [
            [
                PhpToken::tokenize(
                    '<?php class Dummy1 {}'
                ),
                0,
                false,
            ],
            [
                PhpToken::tokenize(
                    '<?php class Dummy1 {}'
                ),
                1,
                true,
            ],
            [
                PhpToken::tokenize(
                    '<?php trait Dummy1 {}'
                ),
                1,
                true,
            ],
            [
                PhpToken::tokenize(
                    '<?php Enum Dummy1 {}'
                ),
                1,
                true,
            ],
            [
                PhpToken::tokenize(
                    '<?php interface Dummy1 {}'
                ),
                1,
                true,
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider isClassProvider
    */
    public function isClass(
        $tokens,
        $position,
        $expect,
    ) {
//      $this->markTestIncomplete();

        $obj = new TypeHintChecker();

        $obj->phpTokens = $tokens;

        $actual = $this->callPrivateMethod(
            $obj,
            'isClass',
            [$position],
        );

        $this->assertEquals(
            $expect,
            $actual,
        );
    }

    public function getClassNameProvider()
    {
        $tokens = $this->phpCodeTokens();

        return [
            [
                $tokens[1],
                17,
                '',
                '\\Dummy1',
            ],
            [
                $tokens[1],
                19,
                'dummy\\tool2',
                'dummy\\tool2\\Dummy1',
            ],
        ];
    }


    /**
    *   @test
    *   @dataProvider getClassNameProvider
    */
    public function getClassName(
        $tokens,
        $position,
        $namespace,
        $expect,
    ) {
//      $this->markTestIncomplete();

        $obj = new TypeHintChecker();

        $obj->phpTokens = $tokens;

        $this->setPrivateProperty(
            $obj,
            'currentNamespace',
            $namespace,
        );

        $newPosition = $this->callPrivateMethod(
            $obj,
            'getClassName',
            [$position],
        );

        $this->assertGreaterThan(
            $position,
            $newPosition,
        );

        $this->assertEquals(
            $expect,
            $obj->className,
        );
    }

    public function checkPropertyProvider()
    {
        $classes = [
            new ReflectionClass(
                SinglePhpClass::class,
            ),
        ];

        return [
            [
                ($classes[0])::class,
                $classes[0],
                [
                    'noTypePublicProp',
                    'noTypeProtectedProp',
                    'noTypePrivateProp',
                ],
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider checkPropertyProvider
    */
    public function checkProperty(
        $className,
        $reflectionClass,
        $expect,
    ) {
//      $this->markTestIncomplete();

        $obj = new TypeHintChecker();

        $obj->className = $className;

        $this->callPrivateMethod(
            $obj,
            'checkProperty',
            [$reflectionClass],
        );

        $this->assertEquals(
            count($expect),
            count($obj->messages),
        );

        foreach ($expect as $name) {
            $isMatche = false;

            foreach ($obj->messages as $message) {
                if (mb_ereg_match(".*{$name}", $message)) {
                    $isMatche = true;
                }
            }

            $this->assertTrue($isMatche);
        }
    }

    public function checkArgumentProvider()
    {
        $classes = [
            new ReflectionClass(
                SinglePhpClass::class,
            ),
        ];

        return [
            [
                ($classes[0])::class,
                'allHasTypeMethod',
                ($classes[0])->getMethod('allHasTypeMethod'),
                [
                ],
            ],
            [
                ($classes[0])::class,
                'hasNoTypeParamMethod',
                ($classes[0])->getMethod('hasNoTypeParamMethod'),
                [
                    'noTypeParam1',
                    'noTypeParam2',
                ],
            ],
            [
                ($classes[0])::class,
                'allNoTypeMethod',
                ($classes[0])->getMethod('allNoTypeMethod'),
                [
                    'noTypeParam1',
                    'noTypeParam2',
                    'noTypeParam3',
                ],
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider checkArgumentProvider
    */
    public function checkArgument(
        $className,
        $methodName,
        $reflectionMethod,
        $expect,
    ) {
//      $this->markTestIncomplete();

        $obj = new TypeHintChecker();

        $obj->className = $className;

        $this->callPrivateMethod(
            $obj,
            'checkArgument',
            [
                $methodName,
                $reflectionMethod
            ],
        );

        $this->assertEquals(
            count($expect),
            count($obj->messages),
        );

        foreach ($expect as $name) {
            $isMatche = false;

            foreach ($obj->messages as $message) {
                if (mb_ereg_match(".*{$name}", $message)) {
                    $isMatche = true;
                }
            }

            $this->assertTrue($isMatche);
        }
    }

    public function singlePhpClassProvider()
    {
        return [
            [
                'SinglePhpClass',
                [
                    '.+SinglePhpClass.+noTypePublicProp',
                    '.+SinglePhpClass.+noTypeProtectedProp',
                    '.+SinglePhpClass.+noTypePrivateProp',

                    '.+SinglePhpClass.+hasNoTypeParamMethod.+noTypeParam1',
                    '.+SinglePhpClass.+hasNoTypeParamMethod.+noTypeParam2',

                    '.+SinglePhpClass.+allNoTypeMethod',
                    '.+SinglePhpClass.+allNoTypeMethod.+noTypeParam1',
                    '.+SinglePhpClass.+allNoTypeMethod.+noTypeParam2',
                    '.+SinglePhpClass.+allNoTypeMethod.+noTypeParam3',
                ]
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider singlePhpClassProvider
    */
    public function singlePhpClass(
        $filename,
        $expect,
    ) {
//      $this->markTestIncomplete();

        $obj = new TypeHintChecker();
        $obj(
            $this->makeStubFilePath($filename),
        );

        $this->assertEquals(
            count($expect),
            count($obj->messages),
        );

        foreach ($expect as $pattern) {
            $isMatche = false;

            foreach ($obj->messages as $message) {
                if (mb_ereg_match(".*{$pattern}", $message)) {
                    $isMatche = true;
                }
            }

            $this->assertTrue($isMatche);
        }
    }

    public function multiPhpClassProvider()
    {
        return [
            [
                'MultiNamespacePhpClass',
                [
                    '.+MultiNamespacePhpClass.+noTypePublicProp',
                    '.+MultiNamespacePhpClass.+noTypeProtectedProp',
                    '.+MultiNamespacePhpClass.+noTypePrivateProp',

                    '.+MultiNamespacePhpClass.+hasNoTypeParamMethod.+noTypeParam1',
                    '.+MultiNamespacePhpClass.+hasNoTypeParamMethod.+noTypeParam2',

                    '.+MultiNamespacePhpClass.+allNoTypeMethod',
                    '.+MultiNamespacePhpClass.+allNoTypeMethod.+noTypeParam1',
                    '.+MultiNamespacePhpClass.+allNoTypeMethod.+noTypeParam2',
                    '.+MultiNamespacePhpClass.+allNoTypeMethod.+noTypeParam3',
                ]
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider multiPhpClassProvider
    */
    public function multiPhpClass(
        $filename,
        $expect,
    ) {
//      $this->markTestIncomplete();

        $obj = new TypeHintChecker();
        $obj(
            $this->makeStubFilePath($filename),
        );

        $this->assertEquals(
            count($expect),
            count($obj->messages),
        );

        foreach ($expect as $pattern) {
            $isMatche = false;

            foreach ($obj->messages as $message) {
                if (mb_ereg_match(".*{$pattern}", $message)) {
                    $isMatche = true;
                }
            }

            $this->assertTrue($isMatche);
        }
    }
}
