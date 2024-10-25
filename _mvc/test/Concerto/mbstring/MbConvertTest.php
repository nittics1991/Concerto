<?php

declare(strict_types=1);

namespace test\Concerto\mbstring;

use test\Concerto\ConcertoTestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use Concerto\mbstring\MbConvert;

class MbConvertTest extends ConcertoTestCase
{
    public static function roma2kanaProvider()
    {
        return [
            ['aiueo', 'あいうえお'],
            ['kakikukeko', 'かきくけこ'],
            ['sasisuseso', 'さしすせそ'],
            ['tatituteto', 'たちつてと'],
            ['naninuneno', 'なにぬねの'],
            ['hahihuheho', 'はひふへほ'],
            ['mamimumemo', 'まみむめも'],
            ['yayuyo', 'やゆよ'],
            ['rarirurero', 'らりるれろ'],
            ['wawonn', 'わをん'],

            ['gagigugego', 'がぎぐげご'],
            ['zazizuzezo', 'ざじずぜぞ'],
            ['dadidudedo', 'だぢづでど'],
            ['babibubebo', 'ばびぶべぼ'],
            ['papipupepo', 'ぱぴぷぺぽ'],

            ['kyakyikyukyekyo', 'きゃきぃきゅきぇきょ'],

            ['syasyisyusyesyo', 'しゃしぃしゅしぇしょ'],
            ['shashishushesho', 'しゃししゅしぇしょ'],

            ['tyatyityutyetyo', 'ちゃちぃちゅちぇちょ'],
            ['thathithuthetho', 'てゃてぃてゅてぇてょ'],
            ['tsatsitsutsetso', 'つぁつぃつつぇつぉ'],

            ['nyanyinyunyenyo', 'にゃにぃにゅにぇにょ'],

            ['hyahyihyuhyehyo', 'ひゃひぃひゅひぇひょ'],

            ['myamyimyumyemyo', 'みゃみぃみゅみぇみょ'],

            ['ryaryiryuryeryo', 'りゃりぃりゅりぇりょ'],

            ['wawiwuwewo', 'わうぃううぇを'],

            ['gyagyigyugyegyo', 'ぎゃぎぃぎゅぎぇぎょ'],

            ['zyazyizyuzyezyo', 'じゃじぃじゅじぇじょ'],

            ['dyadyidyudyedyo', 'ぢゃぢぃぢゅぢぇぢょ'],

            ['byabyibyubyebyo', 'びゃびぃびゅびぇびょ'],

            ['pyapyipyupyepyo', 'ぴゃぴぃぴゅぴぇぴょ'],

            ['cacicuceco', 'かしくせこ'],
            ['cyacyicyucyecyo', 'ちゃちぃちゅちぇちょ'],
            ['chachichuchecho', 'ちゃちちゅちぇちょ'],

            ['fafifufefo', 'ふぁふぃふふぇふぉ'],
            ['fyafyifyufyefyo', 'ふゃふぃふゅふぇふょ'],

            ['jajijujejo', 'じゃじじゅじぇじょ'],
            ['jyajyijyujyejyo', 'じゃじぃじゅじぇじょ'],

            ['lalilulelo', 'ぁぃぅぇぉ'],
            ['lyalyilyulyelyo', 'ゃぃゅぇょ'],

            ['qaqiquqeqo', 'くぁくぃくくぇくぉ'],
            ['qyaqyiqyuqyeqyo', 'くゃくぃくゅくぇくょ'],

            ['vavivuvevo', 'ヴぁヴぃヴヴぇヴぉ'],
            ['vyavyivyuvyevyo', 'ヴゃヴぃヴゅヴぇヴょ'],

            ['xaxixuxexo', 'ぁぃぅぇぉ'],
            ['xyaxyixyuxyexyo', 'ゃぃゅぇょ'],
        ];
    }

    /**
    */
    #[Test]
    #[DataProvider('roma2kanaProvider')]
    public function roma2kana($data, $expect)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertEquals($expect, MbConvert::roma2kana($data));
    }
}
