<?php

declare(strict_types=1);

namespace test\Concerto;

use test\Concerto\ConcertoTestCase;
use Concerto\database\LoginInfData;

class LoginInfDataTest extends ConcertoTestCase
{
    private $class;

    protected function setUp(): void
    {
        $this->class = new LoginInfData();
    }

    public static function successDataSetProvider()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        return [
            ['20141201 000000', '11111ITC', '名前１']
            , ['20141202 123456', '00000ITC', '１２３４５６７８９０']
            , ['20141203 012345', '99999ITC', '']
        ];
    }

    /**
    *
    * @dataProvider successDataSetProvider
    *
    */
    public function testSuccessDataSet($ins_date, $cd_tanto, $nm_tanto)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->class->ins_date = $ins_date;
        $this->class->cd_tanto = $cd_tanto;
        $this->class->nm_tanto = $nm_tanto;

        $this->assertEquals($ins_date, $this->class->ins_date);
        $this->assertEquals($cd_tanto, $this->class->cd_tanto);
        $this->assertEquals($nm_tanto, $this->class->nm_tanto);

        $this->assertTrue(isset($this->class->ins_date));
        $this->assertTrue(isset($this->class->cd_tanto));
        $this->assertTrue(isset($this->class->nm_tanto));
    }

    /**
    */
    public function testFailureDataSet()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->expectException(\InvalidArgumentException::class);
        $this->class->non = '20141203';
    }

    public function testFalseIsset()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertFalse(isset($this->class->ins_date));
    }

    /**
    *
    * @dataProvider successDataSetProvider
    *
    */
    public function testChangeArray()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $array = [
            'ins_date' => '20141231'
            , 'cd_tanto' => '12345ITC'
            , 'nm_tanto' => 'だれかさん'
        ];

        $this->class->fromArray($array);
        $this->assertEquals($array['ins_date'], $this->class->ins_date);
        $this->assertEquals($array, $this->class->toArray());
    }

    /**
    *
    * @dataProvider successDataSetProvider
    *
    */
    public function testSuccessValid($ins_date, $cd_tanto, $nm_tanto)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->class->ins_date = $ins_date;
        $this->class->cd_tanto = $cd_tanto;
        $this->class->nm_tanto = $nm_tanto;

        $this->assertTrue($this->class->isValidIns_date($this->class->ins_date));
        $this->assertTrue($this->class->isValidCd_tanto($this->class->cd_tanto));
        $this->assertTrue($this->class->isValidNm_tanto($this->class->nm_tanto));
        $this->assertTrue($this->class->isValid());
    }

    public static function failureDataSetProvider()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        //$flg => 2進数 ins_date, cd_tanto, nm_tanto, (エラーが出るbitを1)
        return [
            ['20141201', '11111ITC', '名前１', 0b100]
            , ['20141202 123456', '12ITC', '１２３４５６７８９０', 0b010]
            , ['20141203 012345', '99999ITC', '１２３４５６７８９０１', 0b001]
            , ['20141202', '12ITC', '１２３４５６７８９０', 0b110]
            , ['20141203', '99999ITC', '１２３４５６７８９０１', 0b101]
            , ['20141203', 'ITC', '１２３４５６７８９０１', 0b111]
        ];
    }

    /**
    *
    * @dataProvider failureDataSetProvider
    *
    */
    public function testFailureValid($ins_date, $cd_tanto, $nm_tanto, $flg)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->class->ins_date = $ins_date;
        $this->class->cd_tanto = $cd_tanto;
        $this->class->nm_tanto = $nm_tanto;

        $this->assertFalse($this->class->isValid());

        if ($flg & 0b100) {
            $this->assertContains('ins_date', array_keys($this->class->getValidError()));
        }

        if ($flg & 0b010) {
            $this->assertContains('cd_tanto', array_keys($this->class->getValidError()));
        }

        if ($flg & 0b001) {
            $this->assertContains('nm_tanto', array_keys($this->class->getValidError()));
        }
    }
}
