<?php

declare(strict_types=1);

namespace test\Concerto\database;

use test\Concerto\ConcertoTestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use Concerto\database\MstTantoData;
use Concerto\Validate;

class MstTantoDataTest extends ConcertoTestCase
{
    private $class;

    protected function setUp(): void
    {
        $this->class = new MstTantoData();
    }

    public static function successDataSetProvider()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        //27データ
        return [
            [
                '12345ITC', '2012345', '担当１', '12345ITC@glb.toshiba.co.jp', '0'
                , '1', '2', 'ICC12','!12345ITC'
                , '1', 100, 'tanto123'
            ],
            [
                '01234ITC', '2001234', '担当２２２２２２', '01234ITC@glb.toshiba.co.jp', '1'
                , '0', '3', 'ICD22','abcdefg'
                , '1', 0,'12345678'
            ]
        ];
    }

    /**
    *
    *
    */
    #[DataProvider('successDataSetProvider')]
    public function testSuccessDataSet(
        $cd_tanto,
        $disp_seq,
        $nm_tanto,
        $mail_add,
        $kengen,
        $kengen_db,
        $kengen_sm,
        $cd_bumon,
        $password,
        $fg_mail,
        $ri_cyokka,
        $username
    ) {

//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->class->cd_tanto = $cd_tanto;
        $this->class->disp_seq = $disp_seq;
        $this->class->nm_tanto = $nm_tanto;
        $this->class->mail_add = $mail_add;
        $this->class->kengen_db = $kengen_db;
        $this->class->cd_bumon = $cd_bumon;
        $this->class->fg_mail = $fg_mail;
        $this->class->ri_cyokka = $ri_cyokka;
        $this->class->username = $username;

        $this->assertEquals($cd_tanto, $this->class->cd_tanto);
        $this->assertEquals($disp_seq, $this->class->disp_seq);
        $this->assertEquals($nm_tanto, $this->class->nm_tanto);
        $this->assertEquals($mail_add, $this->class->mail_add);
        $this->assertEquals($kengen_db, $this->class->kengen_db);
        $this->assertEquals($cd_bumon, $this->class->cd_bumon);
        $this->assertEquals($fg_mail, $this->class->fg_mail);
        $this->assertEquals($ri_cyokka, $this->class->ri_cyokka);
        $this->assertEquals($username, $this->class->username);

        $this->assertTrue(isset($this->class->cd_tanto));
        $this->assertTrue(isset($this->class->disp_seq));
        $this->assertTrue(isset($this->class->nm_tanto));
        $this->assertTrue(isset($this->class->mail_add));
        $this->assertTrue(isset($this->class->kengen_db));
        $this->assertTrue(isset($this->class->cd_bumon));
        $this->assertTrue(isset($this->class->fg_mail));
        $this->assertTrue(isset($this->class->ri_cyokka));
        $this->assertTrue(isset($this->class->username));
    }

    /**
    *
    *
    */
    #[DataProvider('successDataSetProvider')]
    public function testSuccessValid(
        $cd_tanto,
        $disp_seq,
        $nm_tanto,
        $mail_add,
        $kengen,
        $kengen_db,
        $kengen_sm,
        $cd_bumon,
        $password,
        $fg_mail,
        $ri_cyokka,
        $username
    ) {

//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->class->cd_tanto = $cd_tanto;
        $this->class->disp_seq = $disp_seq;
        $this->class->nm_tanto = $nm_tanto;
        $this->class->mail_add = $mail_add;
        $this->class->kengen_db = $kengen_db;
        $this->class->cd_bumon = $cd_bumon;
        $this->class->fg_mail = $fg_mail;
        $this->class->ri_cyokka = $ri_cyokka;
        $this->class->username = $username;


        $this->assertTrue($this->class->isValidcd_tanto($cd_tanto));
        $this->assertTrue($this->class->isValidDisp_seq($disp_seq));
        $this->assertTrue($this->class->isValidnm_tanto($nm_tanto));
        $this->assertTrue($this->class->isValidMail_add($mail_add));
        $this->assertTrue($this->class->isValidKengen_db($kengen_db));
        $this->assertTrue($this->class->isValidcd_bumon($cd_bumon));
        $this->assertTrue($this->class->isValidFg_mail($fg_mail));
        $this->assertTrue($this->class->isValidRi_cyokka($ri_cyokka));
        $this->assertTrue($this->class->isValidUsername($username));

        $this->assertTrue($this->class->isValid());
    }

    public function testLogin()
    {
        // $password = 'manager';
        // $this->class->password = $password;

        // $this->assertTrue($this->class->isValidPassword($password));
        $this->assertTrue($this->class->isValid());
    }

    /**
    *
    *
    */
    #[DataProvider('successDataSetProvider')]
    public function testSuccessAlias()
    {
        $this->class->cd_tanto    = '98765ITC';
        $this->class->nm_tanto    = 'name';
        $this->class->cd_bumon      = 'ICH02';

        $this->assertEquals($this->class->cd_tanto, $this->class->cd_tanto);
        $this->assertEquals($this->class->nm_tanto, $this->class->nm_tanto);
        $this->assertEquals($this->class->cd_bumon, $this->class->cd_bumon);

        $this->assertEquals(true, isset($this->class->cd_tanto));
        $this->assertEquals(true, isset($this->class->nm_tanto));
        $this->assertEquals(true, isset($this->class->cd_bumon));

        $this->class->cd_tanto    = '12345ITC';
        $this->class->nm_tanto    = '名前';
        $this->class->cd_bumon      = 'ICC12';

        $this->assertEquals($this->class->cd_tanto, $this->class->cd_tanto);
        $this->assertEquals($this->class->nm_tanto, $this->class->nm_tanto);
        $this->assertEquals($this->class->cd_bumon, $this->class->cd_bumon);
    }
}
