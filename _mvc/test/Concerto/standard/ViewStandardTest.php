<?php

declare(strict_types=1);

namespace test\Concerto\standard;

use test\Concerto\ConcertoTestCase;
use Concerto\standard\ViewStandard;
use Concerto\view\{
    FullUrl,
    HtmlPattern
};

/**
*/
class ViewStandardTest extends ConcertoTestCase
{
    private $class;

    protected function setUp(): void
    {
        $this->class = new ViewStandard();
    }

    public function testSuccessAccesser()
    {

//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $str    = 'AB&C"DE"F&G';
        $ar         = ['fast&1', ['2nd&x', '3rd"%$&"xx']];
        $int    = 1234;
        $bool   = true;

        $expect = [$str, $ar, $int, $bool];
        $obj = new ViewStandard($expect);
        $this->assertEquals($expect, $obj->toArray());
    }

    public function testSuccessToHTML()
    {

//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $str    = 'AB&C"DE"F&G';
        $ar         = ['fast&1', ['2nd&x', '3rd"%$&"xx']];
        $int    = 1234;
        $bool   = true;

        $this->class->str   = $str;
        $this->class->ar    = $ar;
        $this->class->int   = $int;
        $this->class->bool  = $bool;

        $this->class->toHTML();

        $this->assertEquals(htmlspecialchars($str, ENT_QUOTES), $this->class->str);
        $this->assertEquals(htmlspecialchars($ar[0], ENT_QUOTES), $this->class->ar[0]);
        $this->assertEquals(htmlspecialchars($ar[1][0], ENT_QUOTES), $this->class->ar[1][0]);
        $this->assertEquals(htmlspecialchars($ar[1][1], ENT_QUOTES), $this->class->ar[1][1]);
        $this->assertEquals(htmlspecialchars((string)$int, ENT_QUOTES), $this->class->int);
        $this->assertEquals(htmlspecialchars((string)$bool, ENT_QUOTES), $this->class->bool);

        //var_dump($this->class);echo "<hr>";
    }

    public function testSuccessToSJIS()
    {

//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $str    = '文字列';
        $ar         = ['漢字', ['かな', '記号＆記号']];
        $int    = 1234;
        $bool   = true;

        $this->class->str   = $str;
        $this->class->ar    = $ar;
        $this->class->int   = $int;
        $this->class->bool  = $bool;

        $this->class->toSJIS();

        $this->assertEquals(mb_convert_encoding($str, 'SJIS', 'UTF8'), $this->class->str);
        $this->assertEquals(mb_convert_encoding($ar[0], 'SJIS', 'UTF8'), $this->class->ar[0]);
        $this->assertEquals(mb_convert_encoding($ar[1][0], 'SJIS', 'UTF8'), $this->class->ar[1][0]);
        $this->assertEquals(mb_convert_encoding($ar[1][1], 'SJIS', 'UTF8'), $this->class->ar[1][1]);
        $this->assertEquals(mb_convert_encoding((string)$int, 'SJIS', 'UTF8'), $this->class->int);
        $this->assertEquals(mb_convert_encoding((string)$bool, 'SJIS', 'UTF8'), $this->class->bool);

        //var_dump($this->class);echo "<hr>";       //漢字が文字化けしないで表示される
    }

    /**
    *   @test
    */
    public function iterator()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $str    = 'AB&C"DE"F&G';
        $ar         = ['fast&1', ['2nd&x', '3rd"%$&"xx']];
        $int    = 1234;
        $bool   = true;

        $expect = compact('str', 'ar', 'int', 'bool');
        $obj = new ViewStandard($expect);

        foreach ($obj as $key => $val) {
            $this->assertEquals($expect[$key], $val);
            // $i++;
        }
    }

    /**
    *   @test
    */
    public function csrf()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertNotNull($this->class->csrf);
    }

    /**
    *   @test
    */
    public function decodeHTML()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $params = [
            'tag1' => '<div><span>aaa</span></div>',
            'tag2' => '<div><span>bbb</span></div>',
            'tag3' => '<div><span>ccc</span></div>',
        ];

        $obj = new ViewStandard($params);
        $obj->toHTML();

        $expect = htmlspecialchars($params['tag2'], ENT_QUOTES);
        $this->assertEquals($expect, $obj->tag2);

        $obj->decodeHTML('tag2');
        $this->assertEquals($params['tag2'], $obj->tag2);
    }

    /**
    *   @test
    */
    public function addHelper()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new ViewStandard();

        $obj->addHelper(
            'test',
            (new Class {
                public function __invoke($argv)
                {
                    return (new \DateTime($argv))->format('Y-m-d');
                }
            })
        );

        $obj->addHelper(
            'reg',
            function ($name) {
                $pattern = [
                    'strinf' => '%s',
                    'int' => '%d',
                ];
                return $pattern[$name] ?? '';
            }
        );
        $this->assertEquals('2018-10-09', $obj->test('20181009'));
        $this->assertEquals('', $obj->reg('dummy'));
        $this->assertEquals('%d', $obj->reg('int'));
    }

    /**
    *   @test
    */
    public function addHelper2()
    {
        $this->markTestIncomplete(
            '--- helper class is candidate library. ---'
        );

        $obj = new ViewStandard();

        $obj->addHelper('url', new FullUrl('http://example.com/'));
        $obj->addHelper('pattern', new HtmlPattern());

        $this->assertEquals('^20\d{2}[01]\d[0-3]\d$', $obj->pattern('ymd'));
        $this->assertEquals('http://example.com/index.php', $obj->url('index.php'));
    }
}
