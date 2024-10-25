<?php

declare(strict_types=1);

namespace test\Concerto\template;

use test\Concerto\ConcertoTestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use Concerto\template\PhpTemplate;

class PhpTemplateTest extends ConcertoTestCase
{
    public $object;

    public function setUp(): void
    {
        parent::setUp();
    }

    /**
    */
    #[Test]
    public function renderException()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('file not found:DUMMY');
        $this->object = new PhpTemplate('DUMMY');
        $this->object->render('DATASET');
    }

    /**
    */
    #[Test]
    public function renderException2()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('dataset required array');
        $this->object = new PhpTemplate(__FILE__);
        $this->object->render('DATASET');
    }

    public static function renderProvider()
    {
        return [
            [
                __DIR__ . '\\data\\template1.txt',
                [
                    'string' => 'シンプルデータ',
                    'dataset' => [
                        ['no' => 1, 'name' => '田中', 'age' => 12],
                        ['no' => 2, 'name' => '鈴木', 'age' => 13],
                        ['no' => 3, 'name' => '木村', 'age' => 19],
                    ],
                ],
                file_get_contents(__DIR__ . '\\data\\expect1.txt')
            ]
        ];
    }

    /**
    */
    #[Test]
    #[DataProvider('renderProvider')]
    public function render($template, $data, $expect)
    {
        //下記メッセージが出てRiskyになる
        //Test code or tested code did not (only) close its own output buffers
        $this->markTestIncomplete(
            '--- this test end dy HTTP REQUEST. ---'
        );

        $this->object = new PhpTemplate($template);
        $actual = $this->object->render($data);
        $this->assertEquals($expect, $actual);
    }
}
