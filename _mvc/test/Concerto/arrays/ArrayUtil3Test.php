<?php

declare(strict_types=1);

namespace test\Concerto\standard;

use test\Concerto\ConcertoTestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use Concerto\standard\ArrayUtil;

class ArrayUtil3Test extends ConcertoTestCase
{
    /**
    *
    */
    #[Test]
    public function compere()
    {
        $src = [
            'database' => [
                'default' => [
                    'adapter' => 'pgsql',
                    'params' => [
                        'host' => 'localhost',
                        'port' => '5432',
                        'dbname' => 'postgres',
                        'user' => 'postgres',
                        'password' => 'manager'
                    ]
                ]
            ]
            , 'log' => [
                'default' => [
                    'stream' => 'err.log',
                    'format' => '%s:%s' . PHP_EOL
                ]
            ]
        ];

        $target = $src;

        $this->assertEquals([], ArrayUtil::compare($target, $src));


        $target['log']['default']['stream'] = 'CHANGE';
        $target['database']['default']['params']['user'] = 'AAAA';

        $expect['log']['default']['stream'] = ['CHANGE', 'err.log'];
        $expect['database']['default']['params']['user'] = ['AAAA', 'postgres'];

        $this->assertEquals($expect, ArrayUtil::compare($target, $src));
    }

    /**
    *   テーブルテストデータ
    *
    */
    public static function tablePovider()
    {
        return [
        [[
          '山田' => [
              'ID' => '001',
              '出身' => '函館',
              'メールアドレス' => 'yamada@example.com',
              '性別' => '女性'
          ],
          '田中' => [
              'ID' => '002',
              '出身' => '仙台',
              'メールアドレス' => 'tanaka@example.com',
              '性別'  => '男性'
          ],
          '高橋' => [
              'ID' => '003',
              '出身' => '札幌',
              'メールアドレス' => 'takahasi@example.com',
              '性別'  => '女性',
          ],
          '井上' => [
              'ID' => '004',
              '出身' => '東京',
              'メールアドレス' => 'inoue@example.com',
              '性別'  => '男性',
          ],
          '小林' => [
              'ID' => '005',
              '出身' => '大阪',
              'メールアドレス' => 'kobayasi@example.com',
              '性別'  => '男性',
          ],
          '森' => [
              'ID' => '006',
              '出身' => '沖縄',
              'メールアドレス' => 'mori@example.com',
              '性別'  => '女性',
          ]
        ]]
        ];
    }

    /**
    */
    #[Test]
    #[DataProvider('tablePovider')]
    public function sameStruct($data)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $ar1 = $data['山田'];
        $ar2 = $data['森'];
        $this->assertEquals(true, ArrayUtil::sameStruct($ar1, $ar2));

        //データ型変更
        $ar2['ID'] = 111;
        $this->assertEquals(false, ArrayUtil::sameStruct($ar1, $ar2));

        //ID変更
        $ar2 = $data['森'];
        unset($ar2['ID']);
        $ar2['id'] = '111';
        $this->assertEquals(false, ArrayUtil::sameStruct($ar1, $ar2));

        //カラム多
        $ar2 = $data['森'];
        $ar2['変更'] = '111';
        $this->assertEquals(false, ArrayUtil::sameStruct($ar1, $ar2));

        //カラム少
        $ar2 = $data['森'];
        unset($ar2['性別']);
        $this->assertEquals(false, ArrayUtil::sameStruct($ar1, $ar2));
    }

    /**
    */
    #[Test]
    #[DataProvider('tablePovider')]
    public function sameKeys($data)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $ar1 = $data['山田'];
        $ar2 = $data['森'];
        $this->assertEquals(true, ArrayUtil::sameKeys($ar1, $ar2));

        //データ型変更
        $ar2['ID'] = 111;
        $this->assertEquals(true, ArrayUtil::sameKeys($ar1, $ar2));

        //ID変更
        $ar2 = $data['森'];
        unset($ar2['ID']);
        $ar2['id'] = '111';
        $this->assertEquals(false, ArrayUtil::sameKeys($ar1, $ar2));

        //カラム多
        $ar2 = $data['森'];
        $ar2['変更'] = '111';
        $this->assertEquals(false, ArrayUtil::sameKeys($ar1, $ar2));

        //カラム少
        $ar2 = $data['森'];
        unset($ar2['性別']);
        $this->assertEquals(false, ArrayUtil::sameKeys($ar1, $ar2));
    }

    /**
    */
    #[Test]
    #[DataProvider('tablePovider')]
    public function isTable($data)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $target = $data;
        $this->assertEquals(true, ArrayUtil::isTable($target, true, true));
        $this->assertEquals(true, ArrayUtil::isTable($target, true, false));
        $this->assertEquals(true, ArrayUtil::isTable($target, false, false));

        //データ型変更
        $target = $data;
        $target['森']['ID'] = 111;
        $this->assertEquals(false, ArrayUtil::isTable($target));    //true, true
        $this->assertEquals(true, ArrayUtil::isTable($target, true, false));
        $this->assertEquals(true, ArrayUtil::isTable($target, false, false));

        //ID変更
        $target = $data;
        unset($target['森']['性別']);
        $target['森']['変更'] = '111';
        $this->assertEquals(false, ArrayUtil::isTable($target));    //true, true
        $this->assertEquals(false, ArrayUtil::isTable($target, true, false));
        $this->assertEquals(true, ArrayUtil::isTable($target, false, false));

        //カラム多
        $target = $data;
        $target['森']['変更'] = '111';
        $this->assertEquals(false, ArrayUtil::isTable($target));    //true, true
        $this->assertEquals(false, ArrayUtil::isTable($target, true, false));
        $this->assertEquals(false, ArrayUtil::isTable($target, false, false));

        //カラム少
        $target = $data;
        unset($target['森']['性別']);
        $this->assertEquals(false, ArrayUtil::isTable($target));    //true, true
        $this->assertEquals(false, ArrayUtil::isTable($target, true, false));
        $this->assertEquals(false, ArrayUtil::isTable($target, false, false));
    }

    public static function isTableProvider()
    {
        return [
            //空2次元
            [
                [
                    [],
                ],
                true,
            ],
            //空1次元
            [
                [
                ],
                true,
            ],
        ];
    }

    /**
    */
    #[Test]
    #[DataProvider('isTableProvider')]
    public function isTable2($data, $expect)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertEquals(
            $expect,
            ArrayUtil::isTable($data, false, false)
        );
    }
}
