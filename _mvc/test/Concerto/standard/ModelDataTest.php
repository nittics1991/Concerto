<?php

declare(strict_types=1);

namespace test\Concerto\standard;

use test\Concerto\ConcertoTestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use DateTime;
use Concerto\standard;

class _ModeData extends standard\ModelData
{
    protected static array $schema = [
        "b_data" => parent::BOOLEAN
        , "i_data" => parent::INTEGER
        , "f_data" => parent::FLOAT
        , "d_data" => parent::DOUBLE
        , "s_data" => parent::STRING
        , "t_data" => parent::DATETIME
    ];

    public function isValidB_data($val)
    {
        return (empty($val)) ?   ['boolean error'] : true;
    }

    public function isValidI_data($val)
    {
        return (empty($val)) ?   ['integer error'] : true;
    }

    public function isValidF_data($val)
    {
        return (empty($val)) ?   ['float error'] : true;
    }

    public function isValidD_data($val)
    {
        return (empty($val)) ?   ['double error'] : true;
    }

    public function isValidS_data($val)
    {
        return (empty($val)) ?   ['string error'] : true;
    }

    public function isValidT_data($val)
    {
        return ($val == new Datetime(date('Ymd'))) ?     ['datetime error'] : true;
    }
}


class TestModelDataAggrigate extends standard\ModelData
{
}

//validateStaticCall用
class _ModeDataValidateStaticCall extends standard\ModelData
{
    protected static array $schema = [
        "b_data" => parent::BOOLEAN
        , "i_data" => parent::INTEGER
        , "f_data" => parent::FLOAT
        , "d_data" => parent::DOUBLE
        , "s_data" => parent::STRING
        , "t_data" => parent::DATETIME
    ];

    public function isValidB_data($val)
    {
        return is_bool($val);
    }

    public function isValidI_data($val)
    {
        return is_int($val);
    }

    public function isValidF_data($val)
    {
        return is_float($val);
    }

    public function isValidD_data($val)
    {
        return is_float($val);
    }

    public function isValidS_data($val)
    {
        return is_string($val);
    }

    public function isValidT_data($val)
    {
        return $val instanceof \DateTimeInterface;
    }
}

////////////////////////////////////////////////////////////////////////////////////////////////////




class ModelDataTest extends ConcertoTestCase
{
    private $class;

    protected function setUp(): void
    {
        $this->class = new _ModeData();
    }

    public function testBooleanData()
    {
        $this->assertNull($this->class->b_data);

        $this->class->b_data = true;
        $this->assertTrue($this->class->b_data);
        $this->assertIsBool($this->class->b_data);

        $this->class->b_data = false;
        $this->assertFalse($this->class->b_data);
        $this->assertIsBool($this->class->b_data);

        $this->class->b_data = null;
        $this->assertEquals(null, $this->class->i_data);
    }

    public function testIntegerData()
    {
        $this->class->i_data = 10;
        $this->assertEquals(10, $this->class->i_data);
        $this->assertIsInt($this->class->i_data);

        $this->class->i_data = -10;
        $this->assertEquals(-10, $this->class->i_data);
        $this->assertIsInt($this->class->i_data);

        $this->class->i_data = 0x10;
        $this->assertEquals(0x10, $this->class->i_data);
        $this->assertIsInt($this->class->i_data);

        $this->class->i_data = null;
        $this->assertEquals(null, $this->class->i_data);
    }

    public function testFloatData()
    {
        $this->class->f_data = -10.25;
        $this->assertEquals(-10.25, $this->class->f_data);
        $this->assertIsFloat($this->class->f_data);

        $this->class->f_data = 10 / 3;
        $this->assertEqualsWithDelta(3.3, $this->class->f_data, 0.1);
        $this->assertIsFloat($this->class->f_data);

        $this->class->f_data = null;
        $this->assertEquals(null, $this->class->f_data);
    }

    public function testDowbleData()
    {
        $this->class->d_data = -10.25;
        $this->assertEquals(-10.25, $this->class->d_data);
        $this->assertIsFloat($this->class->d_data);

        $this->class->d_data = 10 / 3;
        $this->assertEqualsWithDelta(3.3, $this->class->d_data, 0.1);
        $this->assertIsFloat($this->class->d_data);

        $this->class->d_data = null;
        $this->assertEquals(null, $this->class->d_data);
    }

    public function testStringData()
    {
        $this->class->s_data = '漢字';
        $this->assertEquals('漢字', $this->class->s_data);
        $this->assertIsString($this->class->s_data);

        $this->class->s_data = "aaa\tbbb";
        $this->assertEquals("aaa\tbbb", $this->class->s_data);
        $this->assertIsString($this->class->s_data);

        $this->class->s_data = null;
        $this->assertEquals("", $this->class->s_data);

        $this->class->s_data = mb_convert_encoding('漢字', 'SJIS', 'auto');
        $this->assertEquals(mb_convert_encoding('漢字', 'SJIS', 'auto'), $this->class->s_data);
        $this->assertIsString($this->class->s_data);
    }

    public function testDateTimeData()
    {
        $this->class->t_data = new Datetime('2014-12-15 16:45:30');
        $this->assertEquals(new Datetime('2014-12-15 16:45:30'), $this->class->t_data);
        $this->assertInstanceOf('DateTime', $this->class->t_data);

        $this->class->t_data = new Datetime('20141215 164530');
        $this->assertEquals(new Datetime('2014-12-15 16:45:30'), $this->class->t_data);
        $this->assertInstanceOf('DateTime', $this->class->t_data);
    }

    /**
    */

    public function testGetException()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('no property called:aaa');
        $x = $this->class->aaa;
    }

    /**
    */
    public function testSetException()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('no property called:aaa');
        $this->class->aaa = 10;
    }

    public static function arrayDataProvider()
    {
        return [
            [true, -10, -20.20, -30.03, '文字列', '20141216 123456']
        ];
    }


    /**
    */
    #[DataProvider('arrayDataProvider')]
    public function testToArrayData($b_data, $i_data, $f_data, $d_data, $s_data, $t_data)
    {
        $this->class->b_data = $b_data;
        $this->class->i_data = $i_data;
        $this->class->f_data = $f_data;
        $this->class->d_data = $d_data;
        $this->class->s_data = $s_data;
        $this->class->t_data = $t_data;

        $array = $this->class->toArray();
        $this->assertTrue($array['b_data']);
        $this->assertEquals($i_data, $array['i_data']);
        $this->assertEquals($f_data, $array['f_data']);
        $this->assertEquals($d_data, $array['d_data']);
        $this->assertEquals($s_data, $array['s_data']);
        $this->assertEquals(new DateTime($t_data), $array['t_data']);
    }

    /**
    */
    #[DataProvider('arrayDataProvider')]
    public function testFromArrayData($b_data, $i_data, $f_data, $d_data, $s_data, $t_data)
    {
        $expect = [
            'b_data' => $b_data
            , 'i_data' =>  $i_data
            , 'f_data' =>  $f_data
            , 'd_data' =>  $d_data
            , 's_data' =>  $s_data
            , 't_data' =>  $t_data
        ];

        $this->class->fromArray($expect);

        $array = $this->class->toArray();

        $this->assertTrue($array['b_data']);
        $this->assertEquals($i_data, $array['i_data']);
        $this->assertEquals($f_data, $array['f_data']);
        $this->assertEquals($d_data, $array['d_data']);
        $this->assertEquals($s_data, $array['s_data']);
        $this->assertEquals(new DateTime($t_data), $array['t_data']);
    }

    public function testIdValid()
    {
        $this->class->b_data = true;
        $this->class->i_data = -10;
        $this->class->f_data = -20.02;
        $this->class->d_data = -30.03;
        $this->class->s_data = '文字列';
        $this->class->t_data = '201411';

        $this->assertTrue($this->class->isValid());
    }

    public function testGetValidError()
    {
        $this->class->i_data = 0;
        $this->assertFalse($this->class->isValid());
        $this->assertTrue(array_key_exists('i_data', $this->class->getValidError()));
    }

    public function testGetValidErrors()
    {
        $this->class->b_data = false;
        $this->class->i_data = 0;
        $this->class->f_data = 0.0;
        $this->class->d_data = 0.0;
        $this->class->s_data = '';
        $this->class->t_data = date('Ymd');

        $this->class->isValid();
        $msg = $this->class->getValidError();

        $this->assertEquals(['boolean error'], $msg['b_data']);
        $this->assertEquals(['integer error'], $msg['i_data']);
        $this->assertEquals(['float error'], $msg['f_data']);
        $this->assertEquals(['double error'], $msg['d_data']);
        $this->assertEquals(['string error'], $msg['s_data']);
        $this->assertEquals(['datetime error'], $msg['t_data']);
    }

    public function testGetInfo()
    {
        $expect = [
            "b_data" => 'boolean'
            , "i_data" => 'integer'
            , "f_data" => 'double'
            , "d_data" => 'double'
            , "s_data" => 'string'
            , "t_data" => 'datetime'
        ];

        $this->assertEquals($expect, $this->class->getInfo());

        $this->assertEquals(standard\ModelData::INTEGER, $this->class->getInfo('i_data'));
    }

    /**
    */
    public function testGetInfoException()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('no property called:dummy');
        $this->class->getInfo('dummy');
    }

    public function testActionTest()
    {
        $this->assertEquals(false, isset($this->class->i_data));
        $this->assertEquals(null, $this->class->i_data);

        $this->class->i_data = 10;
        $this->assertEquals(true, isset($this->class->i_data));

        $this->class->f_data = 20.0;
        $this->assertEquals(true, isset($this->class->f_data));

        unset($this->class->i_data);
        $this->assertNull($this->class->i_data);
        $this->assertEquals(true, isset($this->class->f_data));
    }

    public function testSuccessIsNull()
    {
        $this->assertTrue($this->class->isNull());
        $this->class->s_data = 'AA';
        $this->assertFalse($this->class->isNull());

        $this->assertFalse($this->class->isNull('s_data'));
        $this->assertTrue($this->class->isNull('i_data'));
    }

    public function testSuccessIsEmpty()
    {
        $this->assertTrue($this->class->isEmpty());

        $this->class->s_data = '';
        $this->assertTrue($this->class->isEmpty());
        $this->assertTrue($this->class->isEmpty('s_data'));

        $this->class->i_data = 0;
        $this->assertTrue($this->class->isEmpty());
        $this->assertTrue($this->class->isEmpty('i_data'));

        $this->class->i_data = 1;
        $this->assertFalse($this->class->isEmpty());
        $this->assertFalse($this->class->isEmpty('i_data'));
        $this->assertTrue($this->class->isEmpty('s_data'));
    }

    public function testSuccessArrayObject()
    {
        $this->class->i_data = 5;
        $this->assertEquals($this->class['i_data'], $this->class->i_data);
        $this->class->f_data = 3.2;
        $this->assertEquals($this->class['f_data'], $this->class->f_data);
        $this->class->s_data = 'TEST';
        $this->assertEquals($this->class['s_data'], $this->class->s_data);

        $expect = ['i_data', 'f_data', 's_data'];
        $i = 0;

        foreach ($this->class as $key => $val) {
            $this->assertEquals($expect[$i], $key);
            $i++;
        }
    }

    /**
    */
    #[Test]
    public function unsetAll()
    {
        $this->assertEquals(true, $this->class->isNull());
        $this->class->i_data = 5;
        $this->assertEquals(false, $this->class->isNull());
        $this->class->unsetAll();
        $this->assertEquals(true, $this->class->isNull());
    }

    public static function validateStaticCallDataProvider()
    {
        return [
            ['validB_data', false, true],
            ['validB_data', 12, false],
            ['validI_data', 12, true],
            ['validI_data', "12", false],
            ['validS_data', "12", true],
        ];
    }

    /**
    */
    #[Test]
    #[DataProvider('validateStaticCallDataProvider')]
    public function validateStaticCall($method, $data, $expect)
    {
//       $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertEquals(
            $expect,
            call_user_func(
                [_ModeDataValidateStaticCall::class, $method],
                $data
            )
        );

        $this->assertEquals(
            $expect,
            _ModeDataValidateStaticCall::$method($data)
        );
    }
}
