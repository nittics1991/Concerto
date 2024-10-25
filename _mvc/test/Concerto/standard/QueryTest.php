<?php

declare(strict_types=1);

namespace test\Concerto\standard;

use Concerto\standard\Query;
use test\Concerto\ConcertoTestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;

class _Query extends Query
{
    protected static array $schema = [
        'b_data', 'i_data', 'f_data', 'd_data', 's_data', 'Z_DATA'
    ];

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

class QueryTest extends ConcertoTestCase
{
    private $object;

    protected function setUp(): void
    {
    }

    /**
    */
    #[Test]
    public function getParam()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $_GET['i_data'] = 12;
        $_GET['f_data'] = -9.87;
        $_GET['s_data'] = 'STRING';

        $this->object = new _Query();

        $this->assertEquals(12, $this->object->i_data);
        $this->assertEquals(12, $this->object['i_data']);

        //keep snapshot at the time of _construct
        unset($_GET['i_data']);
        $this->assertEquals(12, $this->object->i_data);

        $this->object = new _Query();
        $this->assertEquals(null, $this->object->i_data);
    }

    /**
    */
    #[Test]
    public function isValid()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $_GET['s_data'] = 'STRING';

        $this->object = new _Query();
        $this->assertEquals(true, $this->object->isValid());

        $_GET['s_data'] = 'STRING' . chr(0x01);
        $this->object = new _Query();
        $this->assertEquals(false, $this->object->isValid());
        $this->assertEquals(['s_data' => ['invalid code']], $this->object->getValidError());

        $_GET = [];
        $_GET['i_data'] = 12.13;
        $this->object = new _Query();
        $this->assertEquals(false, $this->object->isValid());
        $this->assertEquals(['i_data' => ['NG']], $this->object->getValidError());
    }
}
