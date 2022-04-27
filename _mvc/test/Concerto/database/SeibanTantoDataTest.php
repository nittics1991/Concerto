<?php

declare(strict_types=1);

namespace test\Concerto\database;

use test\Concerto\ConcertoTestCase;
use Concerto\database\SeibanTantoData;

class SeibanTantoDataTest extends ConcertoTestCase
{
    private $class;

    protected function setUp(): void
    {
        $this->class = new SeibanTantoData();
    }

    public static function successValidate()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        return [
            [
                '20141029 124559', 'ICH12345', '91234ITC', '11', 'CH99'
            ]
        ];
    }

    /**
    *
    * @dataProvider successValidate
    *
    */
    public function testSuccessValidate(
        $ins_date,
        $no_cyu,
        $cd_tanto,
        $no_seq,
        $no_ko
    ) {

//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->class->ins_date      = $ins_date;
        $this->class->no_cyu        = $no_cyu;
        $this->class->cd_tanto      = $cd_tanto;
        $this->class->no_seq        = $no_seq;
        $this->class->no_ko             = $no_ko;

        $this->assertTrue($this->class->isValidIns_date($ins_date));
        $this->assertTrue($this->class->isValidNo_cyu($no_cyu));
        $this->assertTrue($this->class->isValidCd_tanto($cd_tanto));
        $this->assertTrue($this->class->isValidNo_seq($no_seq));
        $this->assertTrue($this->class->isValidNo_ko($no_ko));

        $this->assertTrue($this->class->isValid());
    }

    public static function failureValidate()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        return [
            [
                '20141029', 'ICH123456', '91234IT', 'a', 'CH9900'
            ]
        ];
    }

    /**
    *
    * @dataProvider failureValidate
    *
    */
    public function testFailuresValidate(
        $ins_date,
        $no_cyu,
        $cd_tanto,
        $no_seq,
        $no_ko
    ) {

//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->class->ins_date      = $ins_date;
        $this->class->no_cyu        = $no_cyu;
        $this->class->cd_tanto      = $cd_tanto;
        $this->class->no_seq        = $no_seq;
        $this->class->no_ko             = $no_ko;

        $this->assertFalse($this->class->isValidIns_date($ins_date));
        $this->assertFalse($this->class->isValidNo_cyu($no_cyu));
        $this->assertFalse($this->class->isValidCd_tanto($cd_tanto));
        $this->assertFalse($this->class->isValidNo_seq($no_seq));
        $this->assertFalse($this->class->isValidNo_ko($no_ko));

        $this->assertFalse($this->class->isValid());
    }
}
