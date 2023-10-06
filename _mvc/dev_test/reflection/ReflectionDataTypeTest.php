<?php

declare(strict_types=1);

namespace test\Concerto\reflection;

use PHPUnit\Framework\TestCase;
use test\Concerto\PrivateTestTrait;
use Concerto\reflection\ReflectionDataType;
use ArrayObject;
use Countable;
use DateTime;
use ReflectionClass;
use ReflectionType;
use stdClass;
use test\Concerto\reflection\tester\{
    ReflectionDataTypeTester1,
    ReflectionDataTypeTester2,
};

class ReflectionDataTypeTest extends TestCase
{
    use PrivateTestTrait;

    protected function setUp(): void
    {
    }

    public function createTester(): void
    {
        $this->tester1 = new ReflectionClass(
            ReflectionDataTypeTester1::class,
        );

        //has IteratorAggregate&Countable
        $arrayObject = new ReflectionDataTypeTester2(
            'intersect_iterator_countable',
            new ArrayObject([1, 2, 3, 4, 5]),
        );

        $this->tester2 = new ReflectionClass(
            $arrayObject,
        );

        $this->tester11 = new ReflectionDataTypeTester1(
            'intersect_iterator_countable',
            new ArrayObject([1, 2, 3, 4, 5]),
        );
    }

    public function extractFromProvider()
    {
        $this->createTester();
        return [
            //type=null
            [
                null,
                [],
            ],
            //type=ReflectionNamedType
            [
                $this->tester1->getProperty('bool')
                    ->getType(),
                ['bool'],
            ],
            //type=ReflectionUnionType
            [
                $this->tester1->getProperty('union_int_float')
                    ->getType(),
                ['int', 'float'],
            ],
            //type=nullable
            [
                $this->tester1->getProperty('nullable_bool')
                    ->getType(),
                ['bool'],
            ],
            //type=union has null
            [
                $this->tester1->getProperty('union_int_float_null')
                    ->getType(),
                ['int', 'float', 'null'],
            ],
            //type=ReflectionIntersectionType
            [
                $this->tester1->getProperty('intersect_iterator_countable')
                    ->getType(),
                ['IteratorAggregate', 'Countable'],
            ],
            //method type
            [
                $this->tester1->getMethod('retrunCallable')
                    ->getReturnType(),
                ['callable'],
            ],
            //type=user class
            [
                $this->tester1->getProperty('tester1')
                    ->getType(),
                [ReflectionDataTypeTester1::class],
            ],
            //type=user class namespace
            [
                $this->tester1->getProperty('namespace_tester1')
                    ->getType(),
                [
                    'test\\Concerto\\reflection\\tester\\' .
                    ReflectionDataTypeTester1::class
                ],
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider extractFromProvider
    */
    public function extractFrom(
        ?ReflectionType $reflectionType,
        array $expect,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $obj = new ReflectionDataType(
            $reflectionType,
        );

        $actual = $this->getPrivateProperty(
            $obj,
            'dataTypes',
        );

        $this->assertEquals(
            $expect,
            $this->getPrivateProperty(
                $obj,
                'dataTypes',
            ),
        );
    }

    /**
    *   @test
    */
    public function create()
    {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $this->createTester();

        $obj = ReflectionDataType::create(
            $this->tester2->getProperty('bool')
                ->getType(),
        );

        $this->assertInstanceOf(
            ReflectionDataType::class,
            $obj,
        );
    }

    public function getIteratorCountProvider()
    {
        $this->createTester();
        return [
            //
            [
                $this->tester1->getProperty('union_int_float')
                    ->getType(),
                2,
                ['int', 'float'],
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider getIteratorCountProvider
    */
    public function getIteratorCount(
        ?ReflectionType $reflectionType,
        int $expect_count,
        array $expect_type,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $obj = new ReflectionDataType(
            $reflectionType,
        );

        //Countable
        $this->assertEquals(
            $expect_count,
            count($obj),
        );

        //Generrator
        foreach ($obj as $type) {
            $this->assertTrue(
                in_array($type, $expect_type),
            );
        }

        //array
        foreach ($obj->dataTypes() as $type) {
            $this->assertTrue(
                in_array($type, $expect_type),
            );
        }
    }

    public function definedTypeProvider()
    {
        $this->createTester();
        return [
            //
            [
                null,
                false,
            ],
            //
            [
                $this->tester1->getProperty('bool')
                    ->getType(),
                true,
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider definedTypeProvider
    */
    public function definedType(
        ?ReflectionType $reflectionType,
        bool $expect,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $obj = new ReflectionDataType(
            $reflectionType,
        );

        $this->assertEquals(
            $expect,
            $obj->definedType(),
        );
    }

    public function isNamedTypeProvider()
    {
        $this->createTester();
        return [
            //
            [
                null,
                false,
            ],
            //
            [
                $this->tester1->getProperty('bool')
                    ->getType(),
                true,
            ],
            //nullable ture
            [
                $this->tester1->getProperty('nullable_bool')
                    ->getType(),
                true,
            ],
            //
            [
                $this->tester1->getProperty('union_int_float_null')
                    ->getType(),
                false,
            ],
            //
            [
                $this->tester1->getProperty('intersect_iterator_countable')
                    ->getType(),
                false,
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider isNamedTypeProvider
    */
    public function isNamedType(
        ?ReflectionType $reflectionType,
        bool $expect,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $obj = new ReflectionDataType(
            $reflectionType,
        );

        $this->assertEquals(
            $expect,
            $obj->isNamedType(),
        );
    }

    public function isUnionTypeProvider()
    {
        $this->createTester();
        return [
            //
            [
                null,
                false,
            ],
            //
            [
                $this->tester1->getProperty('bool')
                    ->getType(),
                false,
            ],
            //
            [
                $this->tester1->getProperty('nullable_bool')
                    ->getType(),
                false,
            ],
            //
            [
                $this->tester1->getProperty('union_int_float_null')
                    ->getType(),
                true,
            ],
            //
            [
                $this->tester1->getProperty('intersect_iterator_countable')
                    ->getType(),
                false,
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider isUnionTypeProvider
    */
    public function isUnionType(
        ?ReflectionType $reflectionType,
        bool $expect,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $obj = new ReflectionDataType(
            $reflectionType,
        );

        $this->assertEquals(
            $expect,
            $obj->isUnionType(),
        );
    }

    public function isIntersectionTypeProvider()
    {
        $this->createTester();
        return [
            //
            [
                null,
                false,
            ],
            //
            [
                $this->tester1->getProperty('bool')
                    ->getType(),
                false,
            ],
            //
            [
                $this->tester1->getProperty('nullable_bool')
                    ->getType(),
                false,
            ],
            //
            [
                $this->tester1->getProperty('union_int_float_null')
                    ->getType(),
                false,
            ],
            //
            [
                $this->tester1->getProperty('intersect_iterator_countable')
                    ->getType(),
                true,
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider isIntersectionTypeProvider
    */
    public function isIntersectionType(
        ?ReflectionType $reflectionType,
        bool $expect,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $obj = new ReflectionDataType(
            $reflectionType,
        );

        $this->assertEquals(
            $expect,
            $obj->isIntersectionType(),
        );
    }

    public function allowsNullProvider()
    {
        $this->createTester();
        return [
            //
            [
                null,
                false,
            ],
            //
            [
                $this->tester1->getProperty('bool')
                    ->getType(),
                false,
            ],
            //
            [
                $this->tester1->getProperty('nullable_bool')
                    ->getType(),
                true,
            ],
            //
            [
                $this->tester1->getProperty('union_int_float_null')
                    ->getType(),
                true,
            ],
            //
            [
                $this->tester1->getProperty('intersect_iterator_countable')
                    ->getType(),
                false,
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider allowsNullProvider
    */
    public function allowsNull(
        ?ReflectionType $reflectionType,
        bool $expect,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $obj = new ReflectionDataType(
            $reflectionType,
        );

        $this->assertEquals(
            $expect,
            $obj->allowsNull(),
        );
    }

    public function hasProvider()
    {
        $this->createTester();
        return [
            //simple
            [
                $this->tester1->getProperty('bool')
                    ->getType(),
                'int',
                false,
            ],
            //
            [
                $this->tester1->getProperty('bool')
                    ->getType(),
                'bool',
                true,
            ],
            //nullable
            [
                $this->tester1->getProperty('nullable_bool')
                    ->getType(),
                'bool',
                true,
            ],
            //
            [
                $this->tester1->getProperty('nullable_bool')
                    ->getType(),
                'null',
                false,
            ],
            //union_int_float_null
            [
                $this->tester1->getProperty('union_int_float_null')
                    ->getType(),
                'float',
                true,
            ],
            //
            [
                $this->tester1->getProperty('union_int_float_null')
                    ->getType(),
                'string',
                false,
            ],
            //type object
            [
                $this->tester1->getProperty('object')
                    ->getType(),
                'object',
                true,
            ],
            //type class name
            [
                $this->tester1->getProperty('class_stdClass')
                    ->getType(),
                'stdClass',
                true,
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider hasProvider
    */
    public function has(
        ?ReflectionType $reflectionType,
        string $dataType,
        bool $expect,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $obj = new ReflectionDataType(
            $reflectionType,
        );

        $this->assertEquals(
            $expect,
            $obj->has($dataType),
        );
    }

    public function hasMethodsProvider()
    {
        $this->createTester();
        return [
            //
            [
                $this->tester1->getProperty('bool')
                    ->getType(),
                'hasBool',
                true,
            ],
            //
            [
                $this->tester1->getProperty('int')
                    ->getType(),
                'hasInt',
                true,
            ],
            //
            [
                $this->tester1->getProperty('float')
                    ->getType(),
                'hasFloat',
                true,
            ],
            //
            [
                $this->tester1->getProperty('string')
                    ->getType(),
                'hasString',
                true,
            ],
            //
            [
                $this->tester1->getProperty('union_int_float_null')
                    ->getType(),
                'hasNull',
                true,
            ],
            //
            [
                $this->tester1->getProperty('nullable_bool')
                    ->getType(),
                'hasNull',
                true,
            ],
            //
            [
                $this->tester1->getProperty('object')
                    ->getType(),
                'hasObject',
                true,
            ],
            //
            [
                $this->tester1->getProperty('array')
                    ->getType(),
                'hasArray',
                true,
            ],
            //
            [
                $this->tester1->getProperty('mixed')
                    ->getType(),
                'hasMixed',
                true,
            ],
            //
            [
                $this->tester1->getProperty('iterable')
                    ->getType(),
                'hasIterable',
                true,
            ],
            //union
            [
                $this->tester1->getProperty('union_int_float_null')
                    ->getType(),
                'hasFloat',
                true,
            ],
            //
            [
                $this->tester1->getMethod('retrunCallable')
                    ->getReturnType(),
                'hasCallable',
                true,
            ],
            //
            [
                $this->tester1->getMethod('retrunVoid')
                    ->getReturnType(),
                'hasVoid',
                true,
            ],
            //
            [
                $this->tester1->getMethod('retrunNever')
                    ->getReturnType(),
                'hasNever',
                true,
            ],
            //
            [
                $this->tester2->getProperty('self')
                    ->getType(),
                'hasSelf',
                true,
            ],
            //
            [
                $this->tester2->getProperty('parent')
                    ->getType(),
                'hasParent',
                true,
            ],
            //
            [
                $this->tester2->getMethod('retrunStatic')
                    ->getReturnType(),
                'hasStatic',
                true,
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider hasMethodsProvider
    */
    public function hasMethods(
        ?ReflectionType $reflectionType,
        string $method,
        bool $expect,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $obj = new ReflectionDataType(
            $reflectionType,
        );

        $this->assertEquals(
            $expect,
            $obj->$method(),
        );
    }

    public function hasScalarProvider()
    {
        $this->createTester();
        return [
            //
            [
                $this->tester1->getProperty('bool')
                    ->getType(),
                true,
            ],
            //
            [
                $this->tester1->getProperty('int')
                    ->getType(),
                true,
            ],
            //
            [
                $this->tester1->getProperty('float')
                    ->getType(),
                true,
            ],
            //
            [
                $this->tester1->getProperty('string')
                    ->getType(),
                true,
            ],
            //
            [
                $this->tester1->getProperty('array')
                    ->getType(),
                false,
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider hasScalarProvider
    */
    public function hasScalar(
        ?ReflectionType $reflectionType,
        bool $expect,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $obj = new ReflectionDataType(
            $reflectionType,
        );

        $this->assertEquals(
            $expect,
            $obj->hasScalar(),
        );
    }

    public function satisfiedIntersectionTypeProvider()
    {
        $this->createTester();

        $objects = [
            new ArrayObject([11,12, 13, 14, 15]),
            new Class implements Countable {
                public function count(): int
                {
                    return 11;
                }
            },
            new ArrayObject([11,12, 13, 14, 15]),
        ];

        return [
            //is_a
            [
                $this->tester1->getProperty('intersect_iterator_countable')
                    ->getType(),
                $objects[0],
                true,
            ],
            //
            [
                $this->tester1->getProperty('intersect_iterator_countable')
                    ->getType(),
                $objects[1],
                false,
            ],
            //object
            [
                $this->tester1->getProperty('object')
                    ->getType(),
                $objects[1],
                true,
            ],
            //is_a
            [
                $this->tester2->getProperty('tester1')
                    ->getType(),
                new ReflectionDataTypeTester2(),
                true,
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider satisfiedIntersectionTypeProvider
    */
    public function satisfiedIntersectionType(
        ?ReflectionType $reflectionType,
        object $value,
        bool $expect,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $obj = new ReflectionDataType(
            $reflectionType,
        );

        $this->assertEquals(
            $expect,
            $this->callPrivateMethod(
                $obj,
                'satisfiedIntersectionType',
                [$value],
            ),
        );
    }

    public function satisfiedObjectProvider()
    {
        $this->createTester();

        return [
            //
            [
                $this->tester1->getProperty('object')
                    ->getType(),
                new stdClass(),
                'object',
                true,
            ],
            //
            [
                $this->tester1->getProperty('class_stdClass')
                    ->getType(),
                new stdClass(),
                'stdClass',
                true,
            ],
            //
            [
                $this->tester1->getProperty('tester1')
                    ->getType(),
                new stdClass(),
                'Dummy',
                false,
            ],
            //
            [
                $this->tester1->getProperty('tester1')
                    ->getType(),
                new ReflectionDataTypeTester1(),
                ReflectionDataTypeTester1::class,
                true,
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider satisfiedObjectProvider
    */
    public function satisfiedObject(
        ?ReflectionType $reflectionType,
        object $value,
        string $type,
        bool $expect,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $obj = new ReflectionDataType(
            $reflectionType,
        );

        $this->assertEquals(
            $expect,
            $this->callPrivateMethod(
                $obj,
                'satisfiedObject',
                [$value, $type],
            ),
        );
    }

    public function satisfiedProvider()
    {
        $this->createTester();

        return [
            //
            [
                null,
                null,
                false,
            ],
            //
            [
                $this->tester1->getProperty('bool')
                    ->getType(),
                true,
                true,
            ],
            //
            [
                $this->tester1->getProperty('bool')
                    ->getType(),
                'dummy',
                false,
            ],
            //
            [
                $this->tester1->getProperty('int')
                    ->getType(),
                12,
                true,
            ],
            //
            [
                $this->tester1->getProperty('float')
                    ->getType(),
                12.4,
                true,
            ],
            //
            [
                $this->tester1->getProperty('string')
                    ->getType(),
                "string",
                true,
            ],
            //
            [
                $this->tester1->getProperty('union_int_float_null')
                    ->getType(),
                -33.5,
                true,
            ],
            //
            [
                $this->tester1->getProperty('nullable_bool')
                    ->getType(),
                null,
                true,
            ],
            //
            [
                $this->tester1->getProperty('union_int_float_null')
                    ->getType(),
                null,
                true,
            ],
            //
            [
                $this->tester1->getProperty('array')
                    ->getType(),
                [],
                true,
            ],
            //
            [
                $this->tester1->getProperty('mixed')
                    ->getType(),
                'mixed',
                true,
            ],
            //
            [
                $this->tester1->getProperty('mixed')
                    ->getType(),
                null,
                true,
            ],
            //
            [
                $this->tester1->getProperty('object')
                    ->getType(),
                new ArrayObject([]),
                true,
            ],
            //
            [
                $this->tester1->getProperty('class_stdClass')
                    ->getType(),
                new stdClass(),
                true,
            ],
            //
            [
                $this->tester1->getProperty('interface_dateTimeInterface')
                    ->getType(),
                new DateTime(),
                true,
            ],
            //
            [
                $this->tester1->getProperty('intersect_iterator_countable')
                    ->getType(),
                new ArrayObject([]),
                true,
            ],
            //
            [
                $this->tester1->getProperty('intersect_iterator_countable')
                    ->getType(),
                new stdClass(),
                false,
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider satisfiedProvider
    */
    public function satisfied(
        ?ReflectionType $reflectionType,
        mixed $value,
        bool $expect,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $obj = new ReflectionDataType(
            $reflectionType,
        );

        $this->assertEquals(
            $expect,
            $obj->satisfied(
                $value,
            ),
        );
    }
}
