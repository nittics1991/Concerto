<?php

declare(strict_types=1);

namespace candidate_test\pattern;

use test\Concerto\ConcertoTestCase;
use candidate\pattern\Sanitize;

class SanitizeTest extends ConcertoTestCase
{
    /**
    *   金額(+/- カンマ区切り 小数許可) => 数値
    */
    public function testSuccessMoneyToNumber()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertEquals('-1', Sanitize::moneyToNumber('-1'));
        $this->assertEquals('-123456', Sanitize::moneyToNumber('-123456'));
        $this->assertEquals('-1234.56', Sanitize::moneyToNumber('-1234.56'));
        $this->assertEquals('-1234', Sanitize::moneyToNumber('-1,234'));
        $this->assertEquals('-1234.56', Sanitize::moneyToNumber('-1,234.56'));
        $this->assertEquals('-123456', Sanitize::moneyToNumber('-1,234e56'));
    }

    /**
    *   Email文字列フィルタ
    *
    */
    public function testSuccessStrToEmail()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $actual     = " aaa.bbb@ toshiba.co.jp ";
        $except     = "aaa.bbb@toshiba.co.jp";
        $this->assertEquals($except, Sanitize::outlookToEmail($actual));

        $actual     = "yata masao(谷田 正雄 ＩＴＣ （生管Ｃ）（生管）) <masao.yata@glb.toshiba.co.jp>;; yata masao(谷田 正雄 ＩＴＣ （生管Ｃ）（生管）) <masao.yata@glb.toshiba.co.jp>; uotani kazunori(魚谷 一則 ＩＴＣ （ＳＩジ）［産環Ｓブ］（産環品）) <kazunori.uotani@glb.toshiba.co.jp>; sanko akira(三光 聡 ＩＴＣ （ＳＩジ）［産環Ｓブ］（産Ｓ３）) <akira.sanko@glb.toshiba.co.jp>; hiraoka fujio(平岡 富士雄 ＩＴＣ （ＳＩジ）［産環Ｓブ］（産Ｓ３）) <fujio.hiraoka@glb.toshiba.co.jp>; sasamura eiichi(笹村 栄一 ＩＴＣ （ＳＩジ）［産環Ｓブ］（産環品）) <eiichi.sasamura@glb.toshiba.co.jp>; kurata hideshi(倉田 英志 ＩＴＣ （ＳＩジ）［産環Ｓブ］（西ＳＣ）) <hideshi.kurata@toshiba.co.jp>; masakazu3.matsushita@glb.toshiba.co.jp";
        $except     = "masao.yata@glb.toshiba.co.jp;kazunori.uotani@glb.toshiba.co.jp;akira.sanko@glb.toshiba.co.jp;fujio.hiraoka@glb.toshiba.co.jp;eiichi.sasamura@glb.toshiba.co.jp;hideshi.kurata@toshiba.co.jp;masakazu3.matsushita@glb.toshiba.co.jp";
        $this->assertEquals($except, Sanitize::outlookToEmail($actual));
    }
}
