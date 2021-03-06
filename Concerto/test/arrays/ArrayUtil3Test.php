<?php

declare(strict_types=1);

namespace Concerto\test\standard;

use Concerto\test\ConcertoTestCase;
use Concerto\standard\ArrayUtil;

class ArrayUtil3Test extends ConcertoTestCase
{
    /**
    *
    *   @test
    */
    public function compere()
    {
        $src = array(
            'database' => array (
                'default' => array(
                    'adapter' => 'pgsql',
                    'params' => array(
                        'host' => 'localhost',
                        'port' => '5432',
                        'dbname' => 'postgres',
                        'user' => 'postgres',
                        'password' => 'manager'
                    )
                )
            )
            , 'log' => array(
                'default' => array(
                    'stream' => 'err.log',
                    'format' => '%s:%s' . PHP_EOL
                )
            )
        );

        $target = $src;

        $this->assertEquals(array(), ArrayUtil::compare($target, $src));


        $target['log']['default']['stream'] = 'CHANGE';
        $target['database']['default']['params']['user'] = 'AAAA';

        $expect['log']['default']['stream'] = array('CHANGE', 'err.log');
        $expect['database']['default']['params']['user'] = array('AAAA', 'postgres');

        $this->assertEquals($expect, ArrayUtil::compare($target, $src));
    }

    /**
    *   テーブルテストデータ
    *
    */
    public function providerTable()
    {
        return array(
        array(array(
          '山田' => array(
              'ID' => '001',
              '出身' => '函館',
              'メールアドレス' => 'yamada@example.com',
              '性別' => '女性'
          ),
          '田中' => array(
              'ID' => '002',
              '出身' => '仙台',
              'メールアドレス' => 'tanaka@example.com',
              '性別'  => '男性'
          ),
          '高橋' => array(
              'ID' => '003',
              '出身' => '札幌',
              'メールアドレス' => 'takahasi@example.com',
              '性別'  => '女性',
          ),
          '井上' => array(
              'ID' => '004',
              '出身' => '東京',
              'メールアドレス' => 'inoue@example.com',
              '性別'  => '男性',
          ),
          '小林' => array(
              'ID' => '005',
              '出身' => '大阪',
              'メールアドレス' => 'kobayasi@example.com',
              '性別'  => '男性',
          ),
          '森' => array(
              'ID' => '006',
              '出身' => '沖縄',
              'メールアドレス' => 'mori@example.com',
              '性別'  => '女性',
          )
        ))
        );
    }

    /**
    *   @test
    *   @dataProvider providerTable
    */
    public function sameStruct($data)
    {
//      $this->markTestIncomplete();

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

        //順序不同
        $ar2 = $data['森'];
        $ar2 = ArrayUtil::rotate($ar2);
        $this->assertEquals(false, ArrayUtil::sameStruct($ar1, $ar2));
    }


    /**
    *   @test
    *   @dataProvider providerTable
    */
    public function sameKeys($data)
    {
//      $this->markTestIncomplete();

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

        //順序不同
        $ar2 = $data['森'];
        $ar2 = ArrayUtil::rotate($ar2);
        $this->assertEquals(true, ArrayUtil::sameKeys($ar1, $ar2));
    }

    /**
    *   @test
    *   @dataProvider providerTable
    */
    public function isTable($data)
    {
//      $this->markTestIncomplete();

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
}
