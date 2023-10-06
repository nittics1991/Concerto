<?php

declare(strict_types=1);

namespace test\Concerto\database;

use test\Concerto\ConcertoTestCase;
use Concerto\database\CyokkaMonKeikakuData;

class CyokkaMonKeikakuDataTest extends ConcertoTestCase
{
    private $class;

    protected function setUp(): void
    {
        $this->class = new CyokkaMonKeikakuData();
    }

    public static function successValidate()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        return [
            [
                '2014S', 'ICH12', '201411', 20,
                160.0, 7.75, 40.0, 90.3, 120.5,
                199.5, 40.3, 200.1, 400, 100,
                80
            ]
        ];
    }

    /**
    *
    * @dataProvider successValidate
    *
    */
    public function testSuccessValidate(
        $kb_nendo,
        $cd_bumon,
        $dt_yyyymm,
        $dt_kado,
        $tm_zitudo,
        $tm_teizikan,
        $tm_zangyo,
        $tm_cyokka,
        $tm_zitudo_m,
        $tm_teizikan_m,
        $tm_zangyo_m,
        $tm_cyokka_m,
        $tm_hoyu_cyokka,
        $yn_yosan,
        $yn_soneki
    ) {

//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->class->kb_nendo          = $kb_nendo;
        $this->class->cd_bumon          = $cd_bumon;
        $this->class->dt_yyyymm             = $dt_yyyymm;
        $this->class->dt_kado           = $dt_kado;
        $this->class->tm_zitudo             = $tm_zitudo;
        $this->class->tm_teizikan       = $tm_teizikan;
        $this->class->tm_zangyo             = $tm_zangyo;
        $this->class->tm_cyokka             = $tm_cyokka;
        $this->class->tm_zitudo_m       = $tm_zitudo_m;
        $this->class->tm_teizikan_m         = $tm_teizikan_m;
        $this->class->tm_zangyo_m       = $tm_zangyo_m;
        $this->class->tm_cyokka_m       = $tm_cyokka_m;
        $this->class->tm_hoyu_cyokka    = $tm_hoyu_cyokka;
        $this->class->yn_yosan          = $yn_yosan;
        $this->class->yn_soneki             = $yn_soneki;

        $this->assertTrue($this->class->isValidKb_nendo($kb_nendo));
        $this->assertTrue($this->class->isValidCd_bumon($cd_bumon));
        $this->assertTrue($this->class->isValidDt_yyyymm($dt_yyyymm));
        $this->assertTrue($this->class->isValidDt_kado($dt_kado));
        $this->assertTrue($this->class->isValidTm_zitudo($tm_zitudo));
        $this->assertTrue($this->class->isValidTm_teizikan($tm_teizikan));
        $this->assertTrue($this->class->isValidTm_zangyo($tm_zangyo));
        $this->assertTrue($this->class->isValidTm_cyokka($tm_cyokka));
        $this->assertTrue($this->class->isValidTm_zitudo_m($tm_zitudo_m));
        $this->assertTrue($this->class->isValidTm_teizikan_m($tm_teizikan_m));
        $this->assertTrue($this->class->isValidTm_zangyo_m($tm_zangyo_m));
        $this->assertTrue($this->class->isValidTm_cyokka_m($tm_cyokka_m));
        $this->assertTrue($this->class->isValidTm_hoyu_cyokka($tm_hoyu_cyokka));
        $this->assertTrue($this->class->isValidYn_yosan($yn_yosan));
        $this->assertTrue($this->class->isValidYn_soneki($yn_soneki));

        $this->assertTrue($this->class->isValid());
    }

    public static function failureValidate()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        return [
            [
                '2014A', 'ICH', '20141130', '20',
                '160.0', '7.75', '40.0', '90.3', '120.5',
                '199.5', '40.3', '200.1', '400', '100',
                '80'
            ]
        ];
    }

    /**
    *
    * @dataProvider failureValidate
    *
    */
    public function testFailureValidate(
        $kb_nendo,
        $cd_bumon,
        $dt_yyyymm,
        $dt_kado,
        $tm_zitudo,
        $tm_teizikan,
        $tm_zangyo,
        $tm_cyokka,
        $tm_zitudo_m,
        $tm_teizikan_m,
        $tm_zangyo_m,
        $tm_cyokka_m,
        $tm_hoyu_cyokka,
        $yn_yosan,
        $yn_soneki
    ) {

//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->class->kb_nendo          = $kb_nendo;
        $this->class->cd_bumon          = $cd_bumon;
        $this->class->dt_yyyymm             = $dt_yyyymm;
        $this->class->dt_kado           = $dt_kado;
        $this->class->tm_zitudo             = $tm_zitudo;
        $this->class->tm_teizikan       = $tm_teizikan;
        $this->class->tm_zangyo             = $tm_zangyo;
        $this->class->tm_cyokka             = $tm_cyokka;
        $this->class->tm_zitudo_m       = $tm_zitudo_m;
        $this->class->tm_teizikan_m         = $tm_teizikan_m;
        $this->class->tm_zangyo_m       = $tm_zangyo_m;
        $this->class->tm_cyokka_m       = $tm_cyokka_m;
        $this->class->tm_hoyu_cyokka    = $tm_hoyu_cyokka;
        $this->class->yn_yosan          = $yn_yosan;
        $this->class->yn_soneki             = $yn_soneki;

        $this->assertFalse($this->class->isValidKb_nendo($kb_nendo));
        $this->assertFalse($this->class->isValidCd_bumon($cd_bumon));
        $this->assertFalse($this->class->isValidDt_yyyymm($dt_yyyymm));
        $this->assertFalse($this->class->isValidDt_kado($dt_kado));
        $this->assertFalse($this->class->isValidTm_zitudo($tm_zitudo));
        $this->assertFalse($this->class->isValidTm_teizikan($tm_teizikan));
        $this->assertFalse($this->class->isValidTm_zangyo($tm_zangyo));
        $this->assertFalse($this->class->isValidTm_cyokka($tm_cyokka));
        $this->assertFalse($this->class->isValidTm_zitudo_m($tm_zitudo_m));
        $this->assertFalse($this->class->isValidTm_teizikan_m($tm_teizikan_m));
        $this->assertFalse($this->class->isValidTm_zangyo_m($tm_zangyo_m));
        $this->assertFalse($this->class->isValidTm_cyokka_m($tm_cyokka_m));
        $this->assertFalse($this->class->isValidTm_hoyu_cyokka($tm_hoyu_cyokka));
        $this->assertFalse($this->class->isValidYn_yosan($yn_yosan));
        $this->assertFalse($this->class->isValidYn_soneki($yn_soneki));

        $this->assertFalse($this->class->isValid());
    }
}
