<?php

declare(strict_types=1);

namespace test\Concerto;

use test\Concerto\PrivateTestTraitTest;

class StubClassParent
{
    private $prop_p1 = 'private property';
    protected $prop_p2 = 'protected property';
    private static $prop_p11 = 'private static property';
    protected static $prop_p21 = 'protected static property';

    private function methodP1()
    {
        return 'private method';
    }

    private function methodP2()
    {
        return 'protected method';
    }

    private static function methodP11()
    {
        return 'private static method';
    }

    private static function methodP21()
    {
        return 'protected static method';
    }
}

class StubClassChild extends StubClassParent
{
    private $prop_c1 = 'private property';
    protected $prop_c2 = 'protected property';
    private static $prop_c11 = 'private static property';
    protected static $prop_c21 = 'protected static property';

    private function methodC1()
    {
        return 'private method';
    }

    private function methodC2()
    {
        return 'protected method';
    }

    private static function methodC11()
    {
        return 'private static method';
    }

    private static function methodC21()
    {
        return 'protected static method';
    }
}

/////////////////////////////////////////////////////////////////////

class PrivateTestTraitTest extends ConcertoTestCase
{
    public $parent;
    public $child;

    public function setUp(): void
    {
        $this->parent = new StubClassParent();
        $this->child = new StubClassChild();
    }

    /**
    *   @test
    */
    public function privateOfDirectClass()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        //直接クラス
        $object = $this->parent;

        //private property get
        $this->assertEquals(
            'private property',
            $this->getPrivateProperty($object, 'prop_p1')
        );

        //private property set
        $expect = 'DUMMY';
        $this->setPrivateProperty($object, 'prop_p1', $expect);
        $this->assertEquals(
            $expect,
            $this->getPrivateProperty($object, 'prop_p1')
        );

        //private method
        $this->assertEquals(
            'private method',
            $this->callPrivateMethod($object, 'methodP1', [])
        );

        ////////////////////////////////////////////////////////////////////////

        //private property get static
        $this->assertEquals(
            'private static property',
            $this->getPrivateProperty($object, 'prop_p11')
        );

        //private property set static
        $expect = 'DUMMY';
        $this->setPrivateProperty($object, 'prop_p11', $expect);
        $this->assertEquals(
            $expect,
            $this->getPrivateProperty($object, 'prop_p11')
        );

        //private method static
        $this->assertEquals(
            'private static method',
            $this->callPrivateMethod($object, 'methodP11', [])
        );
    }

    /**
    *   @test
    */
    public function protectedOfDirectClass()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        //直接クラス
        $object = $this->parent;

        //protected property get
        $this->assertEquals(
            'protected property',
            $this->getPrivateProperty($object, 'prop_p2')
        );

        //protected property set
        $expect = 'CHANGE';
        $this->setPrivateProperty($object, 'prop_p2', $expect);
        $this->assertEquals(
            $expect,
            $this->getPrivateProperty($object, 'prop_p2')
        );

        //protected method
        $this->assertEquals(
            'protected method',
            $this->callPrivateMethod($object, 'methodP2', [])
        );

        ////////////////////////////////////////////////////////////////////////

        //protected property get static
        $this->assertEquals(
            'protected static property',
            $this->getPrivateProperty($object, 'prop_p21')
        );

        //protected property set static
        $expect = 'DUMMY';
        $this->setPrivateProperty($object, 'prop_p21', $expect);
        $this->assertEquals(
            $expect,
            $this->getPrivateProperty($object, 'prop_p21')
        );

        //protected method static
        $this->assertEquals(
            'protected static method',
            $this->callPrivateMethod($object, 'methodP21', [])
        );
    }

    /**
    *   @test
    *   @see static property　prop_p11　が前のテストでの設定値を引き継ぐので別プロセス化
    */
    public function privateOfInheritClass()
    {
        //@runInSeparateProcessでserialize errorが出る(phpunitのバグ)
        $this->markTestIncomplete(
            '--- Serialization of Closure is not allowed ---'
        );

        //継承クラス
        $object = $this->child;

        //private property get
        $this->assertEquals(
            'private property',
            $this->getPrivateProperty($object, 'prop_p1')
        );

        //private property set
        $expect = 'DUMMY';
        $this->setPrivateProperty($object, 'prop_p1', $expect);
        $this->assertEquals(
            $expect,
            $this->getPrivateProperty($object, 'prop_p1')
        );

        //private method
        $this->assertEquals(
            'private method',
            $this->callPrivateMethod($object, 'methodP1', [])
        );

        ////////////////////////////////////////////////////////////////////////

        //private property get static
        $this->assertEquals(
            'private static property',
            $this->getPrivateProperty($object, 'prop_p11')
        );

        //private property set static
        $expect = 'DUMMY';
        $this->setPrivateProperty($object, 'prop_p11', $expect);
        $this->assertEquals(
            $expect,
            $this->getPrivateProperty($object, 'prop_p11')
        );

        //private method static
        $this->assertEquals(
            'private static method',
            $this->callPrivateMethod($object, 'methodP11', [])
        );
    }

    /**
    *   @test
    *   @see static property　prop_p21　が前のテストでの設定値を引き継ぐので別プロセス化
    */
    public function protectedOfInheritClass()
    {
        //@runInSeparateProcessでserialize errorが出る(phpunitのバグ)
        $this->markTestIncomplete(
            '--- Serialization of Closure is not allowed ---'
        );

        //継承クラス
        $object = $this->child;

        //protected property get
        $this->assertEquals(
            'protected property',
            $this->getPrivateProperty($object, 'prop_p2')
        );

        //protected property set
        $expect = 'CHANGE';
        $this->setPrivateProperty($object, 'prop_p2', $expect);
        $this->assertEquals(
            $expect,
            $this->getPrivateProperty($object, 'prop_p2')
        );

        //protected method
        $this->assertEquals(
            'protected method',
            $this->callPrivateMethod($object, 'methodP2', [])
        );

        ////////////////////////////////////////////////////////////////////////

        //protected property get static
        $this->assertEquals(
            'protected static property',
            $this->getPrivateProperty($object, 'prop_p21')
        );

        //protected property set static
        $expect = 'DUMMY';
        $this->setPrivateProperty($object, 'prop_p21', $expect);
        $this->assertEquals(
            $expect,
            $this->getPrivateProperty($object, 'prop_p21')
        );

        //protected method static
        $this->assertEquals(
            'protected static method',
            $this->callPrivateMethod($object, 'methodP21', [])
        );
    }
}
