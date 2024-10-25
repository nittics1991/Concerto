<?php

declare(strict_types=1);

namespace test\Concerto\database;

use test\Concerto\ConcertoTestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use Concerto\database\CyokkaKeikakuData;

class CyokkaKeikakuDataTest extends ConcertoTestCase
{
    private $class;

    protected function setUp(): void
    {
        $this->class = new CyokkaKeikakuData();
    }

    public static function successValidate()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        return [
            [
                '2014S', 'ICH12', '', 1000, 90,
                5950, 40.0, 95
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
        $cd_bumon,
        $cd_bumon_dmy,
        $su_cyokka,
        $ri_cyokka,
        $yn_tanka,
        $tm_zangyo_m,
        $ri_syukkin
    ) {

//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->class->kb_nendo      = $kb_nendo;
        $this->class->cd_bumon      = $cd_bumon;
        // $this->class->cd_bumon_dmy  = $cd_bumon_dmy;
        $this->class->su_cyokka         = $su_cyokka;
        $this->class->ri_cyokka         = $ri_cyokka;
        $this->class->yn_tanka      = $yn_tanka;
        $this->class->tm_zangyo_m   = $tm_zangyo_m;
        $this->class->ri_syukkin    = $ri_syukkin;


        $this->assertTrue($this->class->isValidKb_nendo($kb_nendo));
        $this->assertTrue($this->class->isValidCd_bumon($cd_bumon));
        //$this->assertTrue($this->class->isValidCd_bumon_dmy($cd_bumon_dmy));
        $this->assertTrue($this->class->isValidSu_cyokka($su_cyokka));
        $this->assertTrue($this->class->isValidRi_cyokka($ri_cyokka));
        $this->assertTrue($this->class->isValidYn_tanka($yn_tanka));
        $this->assertTrue($this->class->isValidTm_zangyo_m($tm_zangyo_m));
        $this->assertTrue($this->class->isValidRi_syukkin($ri_syukkin));

        $this->assertTrue($this->class->isValid());
    }

    public static function failureValidate()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        return [
            [
                '2014Z', 'ICH123', '', 1.25, '101',
                -5950, 40, '95'
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
        $cd_bumon,
        $cd_bumon_dmy,
        $su_cyokka,
        $ri_cyokka,
        $yn_tanka,
        $tm_zangyo_m,
        $ri_syukkin
    ) {

//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->class->kb_nendo      = $kb_nendo;
        $this->class->cd_bumon      = $cd_bumon;
        // $this->class->cd_bumon_dmy  = $cd_bumon_dmy;
        $this->class->su_cyokka         = $su_cyokka;
        $this->class->ri_cyokka         = $ri_cyokka;
        $this->class->yn_tanka      = $yn_tanka;
        $this->class->tm_zangyo_m   = $tm_zangyo_m;
        $this->class->ri_syukkin    = $ri_syukkin;

        $this->assertFalse($this->class->isValidKb_nendo($kb_nendo));
        $this->assertFalse($this->class->isValidCd_bumon($cd_bumon));
        //$this->assertFalse($this->class->isValidCd_bumon_dmy($cd_bumon_dmy));
        $this->assertFalse($this->class->isValidSu_cyokka($su_cyokka));
        $this->assertFalse($this->class->isValidRi_cyokka($ri_cyokka));
        $this->assertFalse($this->class->isValidYn_tanka($yn_tanka));
        $this->assertFalse($this->class->isValidTm_zangyo_m($tm_zangyo_m));
        $this->assertFalse($this->class->isValidRi_syukkin($ri_syukkin));

        $this->assertFalse($this->class->isValid());
    }
}
