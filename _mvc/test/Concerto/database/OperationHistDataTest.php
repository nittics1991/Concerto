<?php

declare(strict_types=1);

namespace test\Concerto\database;

use test\Concerto\ConcertoTestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use Concerto\database\OperationHistData;

class OperationHistDataTest extends ConcertoTestCase
{
    private $class;

    protected function setUp(): void
    {
        $this->class = new OperationHistData();
    }

    public static function successValidate()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        return [
            [
                '20141029 124559', '91234ITC', '変更前', '変更後', '1',
                'IBB12345', '2014S', 1
            ]
        ];
    }

    /**
    *
    *
    */
    #[DataProvider('successValidate')]
    public function testSuccessValidate(
        $ins_date,
        $cd_tanto,
        $nm_before,
        $nm_after,
        $nm_table,
        $no_cyu,
        $kb_nendo,
        $no_page
    ) {

//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->class->ins_date      = $ins_date;
        $this->class->cd_tanto      = $cd_tanto;
        $this->class->nm_before         = $nm_before;
        $this->class->nm_after      = $nm_after;
        $this->class->nm_table      = $nm_table;
        $this->class->no_cyu        = $no_cyu;
        // $this->class->kb_nendo      = $kb_nendo;
        $this->class->no_page        = $no_page;

        $this->assertTrue($this->class->isValidIns_date($ins_date));
        $this->assertTrue($this->class->isValidCd_tanto($cd_tanto));
        //$this->assertTrue($this->class->isValidNm_before($nm_before));
        //$this->assertTrue($this->class->isValidNm_after($nm_after));
        $this->assertTrue($this->class->isValidNm_table($nm_table));
        $this->assertTrue($this->class->isValidNo_cyu($no_cyu));
        // $this->assertTrue($this->class->isValidKb_nendo($kb_nendo));
        $this->assertTrue($this->class->isValidNo_page($no_page));

        $this->assertTrue($this->class->isValid());
    }

    public static function failureValidate()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        return [
            [
                '20141029', '91234IT', '変更前', '変更後', '99',
                'IBB', '2014', '1'
            ]
        ];
    }

    /**
    *
    *
    */
    #[DataProvider('failureValidate')]
    public function testFailureValidate(
        $ins_date,
        $cd_tanto,
        $nm_before,
        $nm_after,
        $nm_table,
        $no_cyu,
        $kb_nendo,
        $no_page
    ) {

//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->class->ins_date      = $ins_date;
        $this->class->cd_tanto      = $cd_tanto;
        $this->class->nm_before     = $nm_before;
        $this->class->nm_after      = $nm_after;
        $this->class->nm_table      = $nm_table;
        $this->class->no_cyu        = $no_cyu;
        // $this->class->kb_nendo      = $kb_nendo;
        $this->class->no_page        = $no_page;

        $this->assertFalse($this->class->isValidIns_date($ins_date));
        $this->assertFalse($this->class->isValidCd_tanto($cd_tanto));
        //$this->assertFalse($this->class->isValidNm_before($nm_before));
        //$this->assertFalse($this->class->isValidNm_after($nm_after));
        $this->assertFalse($this->class->isValidNm_table($nm_table));
        $this->assertFalse($this->class->isValidNo_cyu($no_cyu));
        // $this->assertFalse($this->class->isValidKb_nendo($kb_nendo));
        $this->assertFalse($this->class->isValidNo_page($no_page));

        $this->assertFalse($this->class->isValid());
    }
}
