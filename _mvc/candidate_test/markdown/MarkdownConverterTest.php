<?php

declare(strict_types=1);

namespace test\Concerto\markdown;

use test\Concerto\ConcertoTestCase;
use candidate\markdown\MarkdownConverter;

class MarkdownConverterTest extends ConcertoTestCase
{
    public function convertProvider()
    {
        $text2 = <<< EOL
1234567890
1234567890
EOL;

        $html2 = "<p>";
        $html2 .= "1234567890<br />\n";
        $html2 .= "1234567890";
        $html2 .= "</p>";

        $text3 = <<< EOL
5行の文字列のテスト
5行の文字列のテスト
5行の文字列のテスト
5行の文字列のテスト
5行の文字列のテスト
EOL;

        $html3 = "<p>";
        $html3 .= "5行の文字列のテスト<br />\n";
        $html3 .= "5行の文字列のテスト<br />\n";
        $html3 .= "5行の文字列のテスト<br />\n";
        $html3 .= "5行の文字列のテスト<br />\n";
        $html3 .= "5行の文字列のテスト";
        $html3 .= "</p>";

        $text4 = <<< EOL
link&escape
http://www.example.com/test/contents.htm?q1=A&q2=B#zzz
link&escape
EOL;

        $html4 = "<p>";
        $html4 .= "link&amp;escape<br />\n";
        $html4 .= '<a href="http://www.example.com/test/contents.htm?q1=A&amp;q2=B#zzz">';
        $html4 .= "http://www.example.com/test/contents.htm?q1=A&amp;q2=B#zzz</a><br />\n";
        $html4 .= "link&amp;escape";
        $html4 .= "</p>";

        return [
            [
                '1行の文字列のテスト',
                '<p>1行の文字列のテスト</p>',
            ],
            [$text2, $html2],
            [$text3, $html3],
            [$text4, $html4],
        ];
    }

    /**
    *   @test
    *   @dataProvider convertProvider
    */
    public function convertTest($text, $excpect)
    {
//      $this->markTestIncomplete();

        $converter = new MarkdownConverter();
        $actual = $converter->convert($text);
        $this->assertEquals($excpect, $actual);
    }
}
