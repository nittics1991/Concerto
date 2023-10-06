<?php

declare(strict_types=1);

namespace test\Concerto\accessor;

use test\Concerto\ConcertoTestCase;
use candidate\accessor\Enum;

class StubEnum extends Enum
{
        const FIRST = 'first';
        const SECOND = true;
        const THERD = 3;
        const FOURTH = 4.0;
        const FIFTH = 5;
}

//////////////////////////////////////////////////////////////////

class EnumTest extends ConcertoTestCase
{
    public function constructExceptionProvider()
    {
        return [
            ['DUMMY'],
            ['3'],
            [false],
        ];
    }

    /**
    *   @test
    *   @dataProvider constructExceptionProvider
    */
    public function constructException($data)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('not defined');
        $obj = new StubEnum($data);
    }

    public function constructCallProvider()
    {
        return [
            ['first'],
            [3],
            [true],
            [StubEnum::FOURTH]
        ];
    }

    /**
    *   @test
    *   @dataProvider constructCallProvider
    */
    public function constructCall($data)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new StubEnum($data);
        $this->assertInstanceOf(StubEnum::class, $obj);
        $this->assertEquals($data, $obj->getValue());
    }

    /**
    *   @test
    */
    public function methodCall()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new StubEnum(StubEnum::FOURTH);
        $this->assertEquals(4.0, $obj->getValue());
        $this->assertEquals('FOURTH', $obj->getKey());
        $this->assertEquals(
            [
                'FIRST',
                'SECOND',
                'THERD',
                'FOURTH',
                'FIFTH',
            ],
            $obj->getKeys()
        );
        $this->assertEquals(
            [
                'FIRST' => 'first',
                'SECOND' => true,
                'THERD' => 3,
                'FOURTH' => 4.0,
                'FIFTH' => 5,
            ],
            $obj->getValues()
        );

        ob_start();
        echo $obj;
        $actual = ob_get_contents();
        ob_end_clean();

        //not '4.0'
        $this->assertEquals('4', $actual);

        $data = [
            'FIRST' => 'first',
            'SECOND' => true,
            'THERD' => 3,
            'FOURTH' => 4.0,
            'FIFTH' => 5,
        ];

        foreach ($obj as $key => $val) {
            $this->assertEquals(key($data), $key);
            $this->assertEquals(current($data), $val);
            next($data);
        }
    }

    public function staticCallExceptionProvider()
    {
        return [
            ['DUMMY'],
            ['3'],
        ];
    }

    /**
    *   @test
    *   @dataProvider staticCallExceptionProvider
    */
    public function staticCallException($data)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->expectException(\BadMethodCallException::class);
        $this->expectExceptionMessage('not defined');
        $obj = StubEnum::$data();
    }

    public function staticCallProvider()
    {
        return [
            ['FIRST', 'first'],
            ['SECOND', true],
        ];
    }

    /**
    *   @test
    *   @dataProvider staticCallProvider
    */
    public function staticCall($data, $expect)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertEquals(new StubEnum($expect), StubEnum::$data());
    }
}
