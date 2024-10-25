<?php

declare(strict_types=1);

namespace test\Concerto\database;

use test\Concerto\ConcertoTestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use Concerto\database\CyunyuInfData;

class CyunyuInfDataTest extends ConcertoTestCase
{
    private $class;

    protected function setUp(): void
    {
        $this->class = new CyunyuInfData();
    }

    public static function successValidate()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        //24データ
        return [
            [
                '2014S', 'ICH12345', 'PB12', '201503',
                'A', '91234ITC', '20151029', 12.5, 5950,
                10000,  3000, '担当名', '商品',
                '1', 'ICC12', 'KICD00253', 21,
                '20150116 123456', 'symphony', 'FIX9999', '手配詳細'
            ]
        ];
    }

    /**
    *
    *
    */
    #[DataProvider('successValidate')]
    public function testSuccessValidate(
        $kb_nendo,
        $no_cyu,
        $no_ko,
        $dt_kanjyo,
        $cd_genka_yoso,
        $cd_tanto,
        $dt_cyunyu,
        $tm_cyokka,
        $yn_cyokka,
        $yn_cyokuzai,
        $yn_etc,
        $nm_tanto,
        $nm_syohin,
        $kb_cyunyu,
        $cd_bumon,
        $no_cyumon,
        $no_seq,
        $up_date,
        $cd_rev,
        $no_tehai,
        $nm_tehai
    ) {

//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->class->kb_nendo = $kb_nendo;
        $this->class->no_cyu = $no_cyu;
        $this->class->no_ko = $no_ko;
        $this->class->dt_kanjyo = $dt_kanjyo;
        $this->class->cd_genka_yoso  = $cd_genka_yoso;
        $this->class->cd_tanto = $cd_tanto;
        $this->class->dt_cyunyu = $dt_cyunyu;
        $this->class->tm_cyokka = $tm_cyokka;
        $this->class->yn_cyokka = $yn_cyokka;
        $this->class->yn_cyokuzai = $yn_cyokuzai;
        $this->class->yn_etc = $yn_etc;
        $this->class->nm_tanto = $nm_tanto;
        $this->class->nm_syohin = $nm_syohin;
        $this->class->kb_cyunyu = $kb_cyunyu;
        $this->class->cd_bumon = $cd_bumon;
        $this->class->no_cyumon = $no_cyumon;
        $this->class->no_seq = $no_seq;
        $this->class->up_date  = $up_date;
        $this->class->cd_rev = $cd_rev;
        $this->class->no_tehai = $no_tehai;
        $this->class->nm_tehai = $nm_tehai;

        $this->assertTrue($this->class->isValidKb_nendo($kb_nendo));
        $this->assertTrue($this->class->isValidNo_cyu($no_cyu));
        $this->assertTrue($this->class->isValidNo_ko($no_ko));
        $this->assertTrue($this->class->isValidDt_kanjyo($dt_kanjyo));
        $this->assertTrue($this->class->isValidCd_genka_yoso($cd_genka_yoso));
        $this->assertTrue($this->class->isValidCd_tanto($cd_tanto));
        $this->assertTrue($this->class->isValidDt_cyunyu($dt_cyunyu));
        $this->assertTrue($this->class->isValidTm_cyokka($tm_cyokka));
        $this->assertTrue($this->class->isValidYn_cyokka($yn_cyokka));
        $this->assertTrue($this->class->isValidYn_cyokuzai($yn_cyokuzai));
        $this->assertTrue($this->class->isValidYn_etc($yn_etc));
        //$this->assertTrue($this->class->isValidNm_tanto($nm_tanto));
        //$this->assertTrue($this->class->isValidNm_syohin($nm_syohin));
        $this->assertTrue($this->class->isValidKb_cyunyu($kb_cyunyu));
        $this->assertTrue($this->class->isValidCd_bumon($cd_bumon));
        $this->assertTrue($this->class->isValidNo_cyumon($no_cyumon));
        $this->assertTrue($this->class->isValidNo_seq($no_seq));
        $this->assertTrue($this->class->isValidUp_date($up_date));
        $this->assertTrue($this->class->isValidCd_rev($cd_rev));
        //$this->assertTrue($this->class->isValidNo_tehai($no_tehai));
        //$this->assertTrue($this->class->isValidNm_tehai($nm_tehai));

        $this->assertTrue($this->class->isValid());
    }

    public static function failureValidate()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        //24データ
        return [
            [
                '2014Z', 'ICH123450', 'PB2', '201599',
                'E', '91234itc', '20151033', '12.5', '5950',
                '10000', '420', '3000', '担当名', '商品',
                '5', 'ICC', 'ZICD00253', '21',
                '20150239 123456', 111, 'FIX9999', '手配詳細'
            ]
        ];
    }

    /**
    *
    *
    */
    #[DataProvider('failureValidate')]
    public function testFailureValidate(
        $kb_nendo,
        $no_cyu,
        $no_ko,
        $dt_kanjyo,
        $cd_genka_yoso,
        $cd_tanto,
        $dt_cyunyu,
        $tm_cyokka,
        $yn_cyokka,
        $yn_cyokuzai,
        $yn_etc,
        $nm_tanto,
        $nm_syohin,
        $kb_cyunyu,
        $cd_bumon,
        $no_cyumon,
        $no_seq,
        $up_date,
        $cd_rev,
        $no_tehai,
        $nm_tehai
    ) {

//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->class->kb_nendo = $kb_nendo;
        $this->class->no_cyu = $no_cyu;
        $this->class->no_ko = $no_ko;
        $this->class->dt_kanjyo = $dt_kanjyo;
        $this->class->cd_genka_yoso  = $cd_genka_yoso;
        $this->class->cd_tanto = $cd_tanto;
        $this->class->dt_cyunyu = $dt_cyunyu;
        $this->class->tm_cyokka = $tm_cyokka;
        $this->class->yn_cyokka = $yn_cyokka;
        $this->class->yn_cyokuzai = $yn_cyokuzai;
        $this->class->yn_etc = $yn_etc;
        $this->class->nm_tanto = $nm_tanto;
        $this->class->nm_syohin = $nm_syohin;
        $this->class->kb_cyunyu = $kb_cyunyu;
        $this->class->cd_bumon = $cd_bumon;
        $this->class->no_cyumon = $no_cyumon;
        $this->class->no_seq = $no_seq;
        $this->class->up_date  = $up_date;
        $this->class->cd_rev = $cd_rev;
        $this->class->no_tehai = $no_tehai;
        $this->class->nm_tehai = $nm_tehai;

        $this->assertFalse($this->class->isValidKb_nendo($kb_nendo));
        $this->assertFalse($this->class->isValidNo_cyu($no_cyu));
        $this->assertFalse($this->class->isValidNo_ko($no_ko));
        $this->assertFalse($this->class->isValidDt_kanjyo($dt_kanjyo));
        $this->assertFalse($this->class->isValidCd_genka_yoso($cd_genka_yoso));
        $this->assertFalse($this->class->isValidCd_tanto($cd_tanto));
        $this->assertFalse($this->class->isValidDt_cyunyu($dt_cyunyu));
        $this->assertFalse($this->class->isValidTm_cyokka($tm_cyokka));
        $this->assertFalse($this->class->isValidYn_cyokka($yn_cyokka));
        $this->assertFalse($this->class->isValidYn_cyokuzai($yn_cyokuzai));
        $this->assertFalse($this->class->isValidYn_etc($yn_etc));
        //$this->assertFalse($this->class->isValidNm_tanto($nm_tanto));
        //$this->assertFalse($this->class->isValidNm_syohin($nm_syohin));
        $this->assertFalse($this->class->isValidKb_cyunyu($kb_cyunyu));
        $this->assertFalse($this->class->isValidCd_bumon($cd_bumon));
        $this->assertFalse($this->class->isValidNo_cyumon($no_cyumon));
        $this->assertFalse($this->class->isValidNo_seq($no_seq));
        $this->assertFalse($this->class->isValidUp_date($up_date));
        $this->assertFalse($this->class->isValidCd_rev($cd_rev));
        //$this->assertFalse($this->class->isValidNo_tehai($no_tehai));
        //$this->assertFalse($this->class->isValidNm_tehai($nm_tehai));

        $this->assertFalse($this->class->isValid());
    }
}
