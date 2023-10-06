<?php

declare(strict_types=1);

namespace test\Concerto\database;

use test\Concerto\ConcertoTestCase;
use Concerto\database\MailInfData;
use Concerto\Validate;

class MailInfDataTest extends ConcertoTestCase
{
    private $class;

    protected function setUp(): void
    {
        $this->class = new MailInfData();
    }

    public static function successDataSetProvider()
    {
        //14データ
        return [
            [
                '20150806 092145', '12345ITC', '67890ITC', 'TITLE', 'COMMENT',
                'SHA00011', '10', 'SGB02', '2015K', '3',
                '01234ITC@glb.toshiba.co.jp;56789ITC@glb.toshiba.co.jp', 'aaaC@gmail.jp', 'bbbC@gmail.jp;cccC@gmail.jp', 9
            ],
            [
                '20150806 092145', '', '67890ITC', '', '',
                '', '', '', '', '1',
                '', '', '', 1
            ]
        ];
    }

    /**
    *
    * @test
    * @dataProvider successDataSetProvider
    */
    public function SuccessDataSet(
        $ins_date,
        $from_tanto,
        $to_tanto,
        $nm_title,
        $nm_comment,
        $no_cyu,
        $no_seq,
        $cd_bumon,
        $kb_nendo,
        $cd_type,
        $from_adr,
        $to_adr,
        $cc_adr,
        $no_page
    ) {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->class->ins_date = $ins_date;
        $this->class->from_tanto = $from_tanto;
        $this->class->to_tanto = $to_tanto;
        $this->class->nm_title = $nm_title;
        $this->class->nm_comment = $nm_comment;
        $this->class->no_cyu = $no_cyu;
        $this->class->no_seq = $no_seq;
        $this->class->cd_bumon = $cd_bumon;
        $this->class->kb_nendo = $kb_nendo;
        $this->class->cd_type = $cd_type;
        $this->class->from_adr = $from_adr;
        $this->class->to_adr = $to_adr;
        $this->class->cc_adr = $cc_adr;
        $this->class->no_page = $no_page;

        $this->assertEquals($ins_date, $this->class->ins_date);
        $this->assertEquals($from_tanto, $this->class->from_tanto);
        $this->assertEquals($to_tanto, $this->class->to_tanto);
        $this->assertEquals($nm_title, $this->class->nm_title);
        $this->assertEquals($nm_comment, $this->class->nm_comment);
        $this->assertEquals($no_cyu, $this->class->no_cyu);
        $this->assertEquals($no_seq, $this->class->no_seq);
        $this->assertEquals($cd_bumon, $this->class->cd_bumon);
        $this->assertEquals($kb_nendo, $this->class->kb_nendo);
        $this->assertEquals($cd_type, $this->class->cd_type);
        $this->assertEquals($from_adr, $this->class->from_adr);
        $this->assertEquals($to_adr, $this->class->to_adr);
        $this->assertEquals($cc_adr, $this->class->cc_adr);
        $this->assertEquals($no_page, $this->class->no_page);

        $this->assertTrue(isset($this->class->ins_date));
        $this->assertTrue(isset($this->class->from_tanto));
        $this->assertTrue(isset($this->class->to_tanto));
        $this->assertTrue(isset($this->class->nm_title));
        $this->assertTrue(isset($this->class->nm_comment));
        $this->assertTrue(isset($this->class->no_cyu));
        $this->assertTrue(isset($this->class->no_seq));
        $this->assertTrue(isset($this->class->cd_bumon));
        $this->assertTrue(isset($this->class->kb_nendo));
        $this->assertTrue(isset($this->class->cd_type));
        $this->assertTrue(isset($this->class->from_adr));
        $this->assertTrue(isset($this->class->to_adr));
        $this->assertTrue(isset($this->class->cc_adr));
        $this->assertTrue(isset($this->class->no_page));

        $this->assertTrue($this->class->isValidIns_date($ins_date));
        $this->assertTrue($this->class->isValidFrom_tanto($from_tanto));
        $this->assertTrue($this->class->isValidTo_tanto($to_tanto));
        //$this->assertTrue($this->class->isValidNm_title($nm_title));
        //$this->assertTrue($this->class->isValidNm_comment($nm_comment));
        $this->assertTrue($this->class->isValidNo_cyu($no_cyu));
        $this->assertTrue($this->class->isValidNo_seq($no_seq));
        $this->assertTrue($this->class->isValidCd_bumon($cd_bumon));
        $this->assertTrue($this->class->isValidKb_nendo($kb_nendo));
        $this->assertTrue($this->class->isValidCd_type($cd_type));
        $this->assertTrue($this->class->isValidFrom_adr($from_adr));
        $this->assertTrue($this->class->isValidTo_adr($to_adr));
        $this->assertTrue($this->class->isValidCc_adr($cc_adr));
        $this->assertTrue($this->class->isValidNo_page($no_page));

        $this->assertTrue($this->class->isValid());
    }

    public static function failureDataSetProvider()
    {
        //14データ
        return [
            [
                '0150806 092145', '2345ITC', '7890ITC', 'TITLE', 'COMMENT',
                'A00011', 'AA', 'B02', '2015Z', '0',
                '01234ITC', '@gmail.jp', 'bbbC@gmail.jp,cccC@gmail.jp', 'X'
            ]
        ];
    }

    /**
    *
    * @test
    * @dataProvider failureDataSetProvider
    */
    public function FailureDataSet(
        $ins_date,
        $from_tanto,
        $to_tanto,
        $nm_title,
        $nm_comment,
        $no_cyu,
        $no_seq,
        $cd_bumon,
        $kb_nendo,
        $cd_type,
        $from_adr,
        $to_adr,
        $cc_adr,
        $no_page
    ) {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->class->ins_date = $ins_date;
        $this->class->from_tanto = $from_tanto;
        $this->class->to_tanto = $to_tanto;
        $this->class->nm_title = $nm_title;
        $this->class->nm_comment = $nm_comment;
        $this->class->no_cyu = $no_cyu;
        $this->class->no_seq = $no_seq;
        $this->class->cd_bumon = $cd_bumon;
        $this->class->kb_nendo = $kb_nendo;
        $this->class->cd_type = $cd_type;
        $this->class->from_adr = $from_adr;
        $this->class->to_adr = $to_adr;
        $this->class->cc_adr = $cc_adr;
        $this->class->no_page = $no_page;

        $this->assertFalse($this->class->isValidIns_date($ins_date));
        $this->assertFalse($this->class->isValidFrom_tanto($from_tanto));
        $this->assertFalse($this->class->isValidTo_tanto($to_tanto));
        //$this->assertFalse($this->class->isValidNm_title($nm_title));
        //$this->assertFalse($this->class->isValidNm_comment($nm_comment));
        $this->assertFalse($this->class->isValidNo_cyu($no_cyu));
        $this->assertFalse($this->class->isValidNo_seq($no_seq));
        $this->assertFalse($this->class->isValidCd_bumon($cd_bumon));
        $this->assertFalse($this->class->isValidKb_nendo($kb_nendo));
        $this->assertFalse($this->class->isValidCd_type($cd_type));
        $this->assertFalse($this->class->isValidFrom_adr($from_adr));
        $this->assertFalse($this->class->isValidTo_adr($to_adr));
        $this->assertFalse($this->class->isValidCc_adr($cc_adr));
        $this->assertFalse($this->class->isValidNo_page($no_page));

        $this->assertFalse($this->class->isValid());

        $expect = [
            'ins_date' => [''],
            'from_tanto' => [''],
            'to_tanto' => [''],
            'no_cyu' => [''],
            'no_seq' => [''],
            'cd_bumon' => [''],
            'kb_nendo' => [''],
            'cd_type' => [''],
            'from_adr' => [''],
            'to_adr' => [''],
            'cc_adr' => ['']
//          , 'no_page' => ['']    //0で設定される
        ];
        $this->assertEquals($expect, $this->class->getValidError());
    }

    /**
    * @test
    */
    public function parseAddress()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $expect = [
            'zzz.ppp@gmail.co.jp',
            'test_mail.adr@parse.address',
            'mail@mail.inf.com'
        ];

        $actual = implode(';', $expect);
        $this->assertEquals($expect, $this->class->parseAddress($actual));

        $data = array_merge([' '], $expect, [' ']);
        $actual = implode(';', $expect);
        $this->assertEquals($expect, $this->class->parseAddress($actual));

        $data = $expect;
        array_splice($data, 1, 0, [' ']);
        $actual = implode(';', $expect);
        $this->assertEquals($expect, $this->class->parseAddress($actual));
    }
}
