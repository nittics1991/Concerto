<?php

declare(strict_types=1);

namespace test\Concerto\database;

use test\Concerto\ConcertoTestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use Concerto\database\WfNewData;

class WfNewDataTest extends ConcertoTestCase
{
    protected function setUp(): void
    {
    }

    /**
    *   __call メソッド無し
    *
    */
    #[Test]
    public function numArgException1()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('__call error:no method called DUMY');
        $object = new WfNewData();

        $object->DUMY(111);
    }

    /**
    *   dt_XXXカラムvalidate
    *
    */
    #[Test]
    public function baseic()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $object = new WfNewData();

        $object->dt_dra_p = '20150430';

        $this->assertEquals(true, $object->isValidDt_dra_p($object->dt_dra_p));
        $this->assertEquals(true, $object->isValid());

        $object->dt_doc_irai_hw_r = '20151231';
        $this->assertEquals(true, $object->isValid());

        $object->dt_doc_henkyaku_sw_r = 'AAA';
        $this->assertEquals(false, $object->isValidDt_doc_henkyaku_sw_r($object->dt_doc_henkyaku_sw_r));
        $this->assertEquals(false, $object->isValid());
        $this->assertEquals(array('dt_doc_henkyaku_sw_r' => ['']), $object->getValidError());
    }
}
