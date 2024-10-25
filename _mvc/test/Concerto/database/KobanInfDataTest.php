<?php

declare(strict_types=1);

namespace test\Concerto\database;

use test\Concerto\ConcertoTestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use Concerto\database\KobanInfData;

class KobanInfDataTest extends ConcertoTestCase
{
    private $class;

    protected function setUp(): void
    {
        $this->class = new KobanInfData();
    }

    public static function successValidate()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        //30データ
        return [
            [
                '2014S', 'IBC12345', 'CD98',
                'ICH12', '201503', '0', '商品',
                1, 2.0, 3, 4,
                6, 7.0, 8, 9,
                11, 12.0, 13, 14,
                16, '20150312', '20150313', '1'
            ],
            [
                '2015K', 'LS18320', 'CH123',
                'PD123', '201512', '4', '記号<>',
                0, 0.0, 0, 0,
                0, 0.0, 0, 0,
                0, 0.0, 0, 0,
                0, '20150331', '20150228', '0'
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
        $cd_bumon,
        $dt_pkansei_m,
        $kb_cyumon,
        $nm_syohin,
        $yn_tov,
        $tm_pcyokka,
        $yn_pcyokka,
        $yn_pcyokuzai,
        $yn_petc,
        $tm_ycyokka,
        $yn_ycyokka,
        $yn_ycyokuzai,
        $yn_yetc,
        $tm_rcyokka,
        $yn_rcyokka,
        $yn_rcyokuzai,
        $yn_retc,
        $dt_kansei,
        $dt_pkansei,
        $kb_keikaku
    ) {

//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->class->kb_nendo      = $kb_nendo;
        $this->class->no_cyu        = $no_cyu;
        $this->class->no_ko             = $no_ko;
        $this->class->cd_bumon      = $cd_bumon;
        $this->class->dt_pkansei_m  = $dt_pkansei_m;
        $this->class->kb_cyumon         = $kb_cyumon;
        $this->class->nm_syohin         = $nm_syohin;
        $this->class->yn_tov        = $yn_tov;
        $this->class->tm_pcyokka    = $tm_pcyokka;
        $this->class->yn_pcyokka    = $yn_pcyokka;
        $this->class->yn_pcyokuzai  = $yn_pcyokuzai;
        $this->class->yn_petc       = $yn_petc;
        $this->class->tm_ycyokka    = $tm_ycyokka;
        $this->class->yn_ycyokka    = $yn_ycyokka;
        $this->class->yn_ycyokuzai  = $yn_ycyokuzai;
        $this->class->yn_yetc       = $yn_yetc;
        $this->class->tm_rcyokka    = $tm_rcyokka;
        $this->class->yn_rcyokka    = $yn_rcyokka;
        $this->class->yn_rcyokuzai  = $yn_rcyokuzai;
        $this->class->yn_retc       = $yn_retc;
        $this->class->dt_kansei         = $dt_kansei;
        $this->class->dt_pkansei    = $dt_pkansei;
        $this->class->kb_keikaku    = $kb_keikaku;


        $this->assertTrue($this->class->isValidKb_nendo($kb_nendo));
        $this->assertTrue($this->class->isValidNo_cyu($no_cyu));
        $this->assertTrue($this->class->isValidNo_ko($no_ko));
        $this->assertTrue($this->class->isValidCd_bumon($cd_bumon));
        $this->assertTrue($this->class->isValidDt_pkansei_m($dt_pkansei_m));
        $this->assertTrue($this->class->isValidKb_cyumon($kb_cyumon));
        //$this->assertTrue($this->class->isValidNm_syohin($nm_syohin));
        $this->assertTrue($this->class->isValidYn_tov($yn_tov));
        $this->assertTrue($this->class->isValidTm_pcyokka($tm_pcyokka));
        $this->assertTrue($this->class->isValidYn_pcyokka($yn_pcyokka));
        $this->assertTrue($this->class->isValidYn_pcyokuzai($yn_pcyokuzai));
        $this->assertTrue($this->class->isValidYn_petc($yn_petc));
        $this->assertTrue($this->class->isValidTm_ycyokka($tm_ycyokka));
        $this->assertTrue($this->class->isValidYn_ycyokka($yn_ycyokka));
        $this->assertTrue($this->class->isValidYn_ycyokuzai($yn_ycyokuzai));
        $this->assertTrue($this->class->isValidYn_yetc($yn_yetc));
        $this->assertTrue($this->class->isValidTm_rcyokka($tm_rcyokka));
        $this->assertTrue($this->class->isValidYn_rcyokka($yn_rcyokka));
        $this->assertTrue($this->class->isValidYn_rcyokuzai($yn_rcyokuzai));
        $this->assertTrue($this->class->isValidYn_retc($yn_retc));
        $this->assertTrue($this->class->isValidDt_kansei($dt_kansei));
        $this->assertTrue($this->class->isValidDt_pkansei($dt_pkansei));
        $this->assertTrue($this->class->isValidKb_keikaku($kb_keikaku));

        $this->assertTrue($this->class->isValid());
    }


    public static function failureValidate()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        //30データ
        return [
            [
                '2014Z', 'IBC123456', 'CD9876',
                'ICH123', '2015031', 5, '商品',
                '1', '2.0', '3', '4',
                '6', '7.0', '8', '9',
                '11', '12.0', '13', '14',
                '16', '201503123', '201503134', '2'
            ],
            [
                '2014Z', 'IBC123456', 'CD9876',
                'ICH123', '2015031', 5, '商品',
                '1', '2.0', '3', '4',
                '6', '7.0', '8', '9',
                '11', '12.0', '13', '14',
                '16', '201503123', '201503134', '2'
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
        $cd_bumon,
        $dt_pkansei_m,
        $kb_cyumon,
        $nm_syohin,
        $yn_tov,
        $tm_pcyokka,
        $yn_pcyokka,
        $yn_pcyokuzai,
        $yn_petc,
        $tm_ycyokka,
        $yn_ycyokka,
        $yn_ycyokuzai,
        $yn_yetc,
        $tm_rcyokka,
        $yn_rcyokka,
        $yn_rcyokuzai,
        $yn_retc,
        $dt_kansei,
        $dt_pkansei,
        $kb_keikaku
    ) {

//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->class->kb_nendo      = $kb_nendo;
        $this->class->no_cyu        = $no_cyu;
        $this->class->no_ko             = $no_ko;
        $this->class->cd_bumon      = $cd_bumon;
        $this->class->dt_pkansei_m  = $dt_pkansei_m;
        $this->class->kb_cyumon         = $kb_cyumon;
        $this->class->nm_syohin         = $nm_syohin;
        $this->class->yn_tov        = $yn_tov;
        $this->class->tm_pcyokka    = $tm_pcyokka;
        $this->class->yn_pcyokka    = $yn_pcyokka;
        $this->class->yn_pcyokuzai  = $yn_pcyokuzai;
        $this->class->yn_petc       = $yn_petc;
        $this->class->tm_ycyokka    = $tm_ycyokka;
        $this->class->yn_ycyokka    = $yn_ycyokka;
        $this->class->yn_ycyokuzai  = $yn_ycyokuzai;
        $this->class->yn_yetc       = $yn_yetc;
        $this->class->tm_rcyokka    = $tm_rcyokka;
        $this->class->yn_rcyokka    = $yn_rcyokka;
        $this->class->yn_rcyokuzai  = $yn_rcyokuzai;
        $this->class->yn_retc       = $yn_retc;
        $this->class->dt_kansei         = $dt_kansei;
        $this->class->dt_pkansei    = $dt_pkansei;
        $this->class->kb_keikaku    = $kb_keikaku;


        $this->assertFalse($this->class->isValidKb_nendo($kb_nendo));
        $this->assertFalse($this->class->isValidNo_cyu($no_cyu));
        $this->assertFalse($this->class->isValidNo_ko($no_ko));
        $this->assertFalse($this->class->isValidCd_bumon($cd_bumon));
        $this->assertFalse($this->class->isValidDt_pkansei_m($dt_pkansei_m));
        $this->assertFalse($this->class->isValidKb_cyumon($kb_cyumon));
        //$this->assertFalse($this->class->isValidNm_syohin($nm_syohin));
        $this->assertFalse($this->class->isValidYn_tov($yn_tov));
        $this->assertFalse($this->class->isValidTm_pcyokka($tm_pcyokka));
        $this->assertFalse($this->class->isValidYn_pcyokka($yn_pcyokka));
        $this->assertFalse($this->class->isValidYn_pcyokuzai($yn_pcyokuzai));
        $this->assertFalse($this->class->isValidYn_petc($yn_petc));
        $this->assertFalse($this->class->isValidTm_ycyokka($tm_ycyokka));
        $this->assertFalse($this->class->isValidYn_ycyokka($yn_ycyokka));
        $this->assertFalse($this->class->isValidYn_ycyokuzai($yn_ycyokuzai));
        $this->assertFalse($this->class->isValidYn_yetc($yn_yetc));
        $this->assertFalse($this->class->isValidTm_rcyokka($tm_rcyokka));
        $this->assertFalse($this->class->isValidYn_rcyokka($yn_rcyokka));
        $this->assertFalse($this->class->isValidYn_rcyokuzai($yn_rcyokuzai));
        $this->assertFalse($this->class->isValidYn_retc($yn_retc));
        $this->assertFalse($this->class->isValidDt_kansei($dt_kansei));
        $this->assertFalse($this->class->isValidDt_pkansei($dt_pkansei));
        $this->assertFalse($this->class->isValidKb_keikaku($kb_keikaku));

        $this->assertFalse($this->class->isValid());
    }
}
