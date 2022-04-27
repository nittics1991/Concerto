<?php

declare(strict_types=1);

namespace test\Concerto\standard;

use Concerto\standard\Post;
use test\Concerto\ConcertoTestCase;

class _Post extends Post
{
    protected static $schema = [
        'b_data', 'i_data', 'f_data', 'd_data', 's_data', 'Z_DATA'
    ];

    /*
    *   POSTデータは文字列だが、テストは数値などで作ってしまった
    *   データがテキストでないので、テキスト変換を行う
    */
    protected function validCom($key, $val): bool
    {
        if (!is_null($val) && !is_string($val) && !is_array($val)) {
            $val = (string)$val;
        }
        return parent::validCom($key, $val);
    }

    public function isValidB_data($val)
    {
        return is_null($val) || is_bool($val);
    }

    public function isValidI_data($val)
    {
        return (is_null($val) || is_int($val)) ?     true : 'NG';
    }

    public function isValidF_data($val)
    {
        return (is_null($val) || is_float($val)) ?   true : ['type_check_error' => $val];
    }

    public function isValidD_data($val)
    {
        return is_null($val) || is_double($val);
    }

    public function isValidS_data($val)
    {
        return is_null($val) || is_string($val);
    }
}

class _Post2 extends Post
{
    protected static $schema = [
        'b_data', 'i_data', 'f_data', 'd_data', 's_data', 'Z_DATA'
    ];

    protected function getS_data($name)
    {
        return mb_strtoupper($this->data[$name]);
    }
}

////////////////////////////////////////////////////////////////////

class PostTest extends ConcertoTestCase
{
    private $object;

    protected function setUp(): void
    {
    }

    /**
    *   @test
    */
    public function getParam()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $_POST['i_data'] = 12;
        $_POST['f_data'] = -9.87;
        $_POST['s_data'] = 'STRING';

        $this->object = new _Post();

        $this->assertEquals(12, $this->object->i_data);
        $this->assertEquals(12, $this->object['i_data']);

        //keep snapshot at the time of _construct
        unset($_POST['i_data']);
        $this->assertEquals(12, $this->object->i_data);

        $this->object = new _Post();
        $this->assertEquals(null, $this->object->i_data);
    }

    /**
    *   @test
    */
    public function isValid()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $_POST['s_data'] = 'STRING';

        $this->object = new _Post();
        $this->assertEquals(true, $this->object->isValid());

        $_POST['s_data'] = 'STRING' . chr(0x01);
        $this->object = new _Post();
        $this->assertEquals(false, $this->object->isValid());
        $this->assertEquals(['s_data' => ['invalid code']], $this->object->getValidError());

        $_POST = [];
        $_POST['i_data'] = 12.13;
        $this->object = new _Post();
        $this->assertEquals(false, $this->object->isValid());
        $this->assertEquals(['i_data' => ['NG']], $this->object->getValidError());
    }

    /**
    *   @test
    */
    public function isAjax()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $_SERVER['HTTP_X_REQUESTED_WITH'] = 'dumy';
        $_SERVER['HTTP_X_REQUESTED_WITH'] = 'xmlhttprequest';
        $_POST['s_data'] = 'STRING';

        $this->object = new _Post();
        $actual = $this->callPrivateMethod($this->object, 'isAjax', []);
        $this->assertEquals(true, $actual);

        unset($_SERVER['HTTP_X_REQUESTED_WITH']);
        $this->object = new _Post();
        $actual = $this->callPrivateMethod($this->object, 'isAjax', []);
        $this->assertEquals(false, $actual);

        $_SERVER['HTTP_X_REQUESTED_WITH'] = 'dumy';
        unset($_SERVER['HTTP_X_REQUESTED_WITH']);
    }

    /**
    *   @test
    */
    public function getFilter()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $_POST['s_data'] = 'string';
        $obj = new _Post2();
        $this->assertEquals('STRING', $obj->s_data);
        $this->assertEquals('STRING', $obj['s_data']);

        $_POST['s_data'] = '<div>string.</div>textData';
        $obj = new _Post2();
        $this->assertEquals('STRING.TEXTDATA', $obj->s_data);
        $this->assertEquals('STRING.TEXTDATA', $obj['s_data']);
    }
}
