<?php

declare(strict_types=1);

namespace test\Concerto\database;

use test\Concerto\ConcertoTestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use Concerto\database\CyubanInfData;

class CyubanInfDataTest extends ConcertoTestCase
{
    private $class;

    protected function setUp(): void
    {
        $this->class = new CyubanInfData();
    }

    public static function successValidate()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        return [
            [
                '2014S', 'IBC12345', 'ICD12',
                '201501', '2', '商品', '設置',
                '客先', '20150123', '1', 9,
                '20150131', '20150221', 100, 80
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
        $cd_bumon,
        $dt_puriage,
        $kb_cyumon,
        $nm_syohin,
        $nm_setti,
        $nm_user,
        $dt_uriage,
        $kb_keikaku,
        $no_seq,
        $dt_hatuban,
        $dt_hakkou,
        $yn_sp,
        $yn_net
    ) {

//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->class->kb_nendo      = $kb_nendo;
        $this->class->no_cyu        = $no_cyu;
        $this->class->cd_bumon      = $cd_bumon;
        $this->class->dt_puriage    = $dt_puriage;
        $this->class->kb_cyumon         = $kb_cyumon;
        $this->class->nm_syohin         = $nm_syohin;
        $this->class->nm_setti      = $nm_setti;
        $this->class->nm_user       = $nm_user;
        $this->class->dt_uriage         = $dt_uriage;
        $this->class->kb_keikaku    = $kb_keikaku;
        $this->class->no_seq        = $no_seq;
        $this->class->dt_hatuban    = $dt_hatuban;
        $this->class->dt_hakkou         = $dt_hakkou;
        $this->class->yn_sp             = $yn_sp;
        $this->class->yn_net        = $yn_net;

        $this->assertTrue($this->class->isValidKb_nendo($kb_nendo));
        $this->assertTrue($this->class->isValidNo_cyu($no_cyu));
        $this->assertTrue($this->class->isValidCd_bumon($cd_bumon));
        $this->assertTrue($this->class->isValidDt_puriage($dt_puriage));
        $this->assertTrue($this->class->isValidKb_cyumon($kb_cyumon));
        //$this->assertTrue($this->class->isValidNm_syohin($nm_syohin));
        //$this->assertTrue($this->class->isValidNm_setti($nm_setti));
        //$this->assertTrue($this->class->isValidNm_user($nm_user));
        $this->assertTrue($this->class->isValidDt_uriage($dt_uriage));
        $this->assertTrue($this->class->isValidKb_keikaku($kb_keikaku));
        $this->assertTrue($this->class->isValidNo_seq($no_seq));
        $this->assertTrue($this->class->isValidDt_hatuban($dt_hatuban));
        $this->assertTrue($this->class->isValidDt_hakkou($dt_hakkou));
        $this->assertTrue($this->class->isValidYn_sp($yn_sp));
        $this->assertTrue($this->class->isValidYn_net($yn_net));

        $this->assertTrue($this->class->isValid());
    }

    public static function failureValidate()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        return [
            [
                '2014Z', 'IBC123456', 'ICD',
                '20150131', '6', '商品', '設置',
                '客先', '201501', '2', -1,
                '201501', '201502', '100', '80'
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
        $cd_bumon,
        $dt_puriage,
        $kb_cyumon,
        $nm_syohin,
        $nm_setti,
        $nm_user,
        $dt_uriage,
        $kb_keikaku,
        $no_seq,
        $dt_hatuban,
        $dt_hakkou,
        $yn_sp,
        $yn_net
    ) {

//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->class->kb_nendo      = $kb_nendo;
        $this->class->no_cyu        = $no_cyu;
        $this->class->cd_bumon      = $cd_bumon;
        $this->class->dt_puriage    = $dt_puriage;
        $this->class->kb_cyumon         = $kb_cyumon;
        $this->class->nm_syohin         = $nm_syohin;
        $this->class->nm_setti      = $nm_setti;
        $this->class->nm_user       = $nm_user;
        $this->class->dt_uriage         = $dt_uriage;
        $this->class->kb_keikaku    = $kb_keikaku;
        $this->class->no_seq        = $no_seq;
        $this->class->dt_hatuban    = $dt_hatuban;
        $this->class->dt_hakkou         = $dt_hakkou;
        $this->class->yn_sp             = $yn_sp;
        $this->class->yn_net        = $yn_net;

        $this->assertFalse($this->class->isValidKb_nendo($kb_nendo));
        $this->assertFalse($this->class->isValidNo_cyu($no_cyu));
        $this->assertFalse($this->class->isValidCd_bumon($cd_bumon));
        $this->assertFalse($this->class->isValidDt_puriage($dt_puriage));
        $this->assertFalse($this->class->isValidKb_cyumon($kb_cyumon));
        //$this->assertFalse($this->class->isValidNm_syohin($nm_syohin));
        //$this->assertFalse($this->class->isValidNm_setti($nm_setti));
        //$this->assertFalse($this->class->isValidNm_user($nm_user));
        $this->assertFalse($this->class->isValidDt_uriage($dt_uriage));
        $this->assertFalse($this->class->isValidKb_keikaku($kb_keikaku));
        $this->assertFalse($this->class->isValidNo_seq($no_seq));
        $this->assertFalse($this->class->isValidDt_hatuban($dt_hatuban));
        $this->assertFalse($this->class->isValidDt_hakkou($dt_hakkou));
        $this->assertFalse($this->class->isValidYn_sp($yn_sp));
        $this->assertFalse($this->class->isValidYn_net($yn_net));

        $this->assertFalse($this->class->isValid());
    }

    public function testGetKbCyumon()
    {

//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $except = ['受', 'Ａ', 'Ｂ', 'Ｃ', 'Ｄ', '仮'];

        $this->assertEquals($except, $this->class->getKbCyumon());
        $this->assertEquals($except[0], $this->class->getKbCyumon('0'));
        $this->assertEquals($except[1], $this->class->getKbCyumon('1'));
        $this->assertEquals($except[2], $this->class->getKbCyumon('2'));
        $this->assertEquals($except[3], $this->class->getKbCyumon('3'));
        $this->assertEquals($except[4], $this->class->getKbCyumon('4'));
        $this->assertNull($this->class->getKbCyumon('6'));
    }


    public static function validateStaticCallDataProvider()
    {
        return [
            ['validNo_cyu', "ICH30K01", true],
            ['validNo_cyu', "ICH00032", true],
            ['validNo_cyu', "ICH00032A",false],
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
                [CyubanInfData::class, $method],
                $data
            )
        );
    }
}
