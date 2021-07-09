<?php

declare(strict_types=1);

namespace Concerto\test\standard;

use Concerto\test\ConcertoTestCase;
use PHPUnit\DbUnit\DataSet\YamlDataSet as PHPUnit_Extensions_Database_DataSet_YamlDataSet;
use Composer_Autoload_ClassLoader;
use Concerto\test\abstractDatabaseTestCase;
use Closure;
use DateTime;
use Exception;
use ReflectionClass;
use ReflectionMethod;
use StdClass;
use Concerto\standard\ArrayUtil;
use Concerto\standard\ModelData;
use Concerto\standard\ModelDb;
use Symfony\Component\yaml\Yaml;
use Concerto\database as DATABASE;

class _ModelDb extends ModelDb
{
    protected $schema = 'test._modeldb';

    public function getTableName()
    {
        return $this->name;
    }
}

class _ModelData extends ModelData
{
    protected static $schema = array(
        "b_data" => parent::BOOLEAN
        , "i_data" => parent::INTEGER
        , "f_data" => parent::FLOAT
        , "d_data" => parent::DOUBLE
        , "s_data" => parent::STRING
        , "t_data" => parent::DATETIME
    );
}

//copyRecordで使用
class _ModelDbData extends ModelData
{
    protected static $schema = array(
        "b_data" => parent::BOOLEAN
        , "i_data" => parent::INTEGER
        , "f_data" => parent::FLOAT
        , "d_data" => parent::DOUBLE
        , "s_data" => parent::STRING
        , "t_data" => parent::DATETIME
    );
}

//upsertで使用
class _ModelDbUpsert extends ModelDb
{
    protected $schema = 'test._modeldbUpsert';

    public function getTableName()
    {
        return $this->name;
    }
}

//createModelで使用
class _ModelDbUpsertData extends ModelData
{
}

///////////////////////////////////////////////////////////////////////////////////////////////////


class _ModelDbTest extends abstractDatabaseTestCase
{
    private $class;
    private $file;
    private $tablename;

    protected function getDataSet()
    {
        $this->tablename = 'test._modeldb';

        $this->file = __DIR__ . '\\data\\modelDb\\_modeldb.yml';
        $dataSet = new PHPUnit_Extensions_Database_DataSet_YamlDataSet($this->file);
        return $dataSet;
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->class = new _ModelDb(static::$pdo);
    }

    /**
    *   private property get value
    *
    *   @param object 対象オブジェクト
    *   @param string プロパティ名
    *   @return mixed
    */
    public function getPrivateProperty($class, $property)
    {
        $refClass = new ReflectionClass($class);
        $refProp = $refClass->getProperty($property);
        $refProp->setAccessible(true);
        return $refProp->getValue($class);
    }

    /**
    *   private method  call
    *
    *   @param object 対象オブジェクト
    *   @param string メソッド名
    *   @params array 引数
    */
    public function callPrivateMethod($class, $method, $args = array())
    {
        $refMethod = new ReflectionMethod($class, $method);
        $refMethod->setAccessible(true);
        return $refMethod->invokeArgs($class, $args);
    }

    public function testRowCount()
    {
//      $this->markTestIncomplete();

        $this->assertEquals(2, $this->getConnection()->getRowCount($this->tablename));
    }

    public function testSelect()
    {
//      $this->markTestIncomplete();

        $yaml = Yaml::parse(file_get_contents($this->file));
        $dataset = ArrayUtil::orderBy($yaml[$this->tablename], array('i_data'));


        //bool = false対策
        array_walk_recursive($dataset, function (&$val, $key) {
            if (($key == 'b_data') && ($val === 'false')) {
                $val = false;
            }
        });

        //All
        $data = new _ModelData();
        $order = 'i_data';
        $actual = $this->class->select($data, $order);

        for (
            $i = 0, $length = count($dataset);
            $i < $length;
            $i++
        ) {
            $expect = new _modelData();
            $expect->fromArray($dataset[$i]);
            $this->assertEquals($expect, $actual[$i]);
        }

        //WHERE boolean
        $data = new _ModelData();
        $data->b_data = true;
        $actual = $this->class->select($data, $order);

        $expect = new _modelData();
        $expect->fromArray($dataset[1]);

        $this->assertEquals($expect, $actual[0]);

        //WHERE integer
        $data = new _ModelData();
        $data->i_data = -10;
        $actual = $this->class->select($data, $order);

        $expect = new _modelData();
        $expect->fromArray($dataset[0]);

        $this->assertEquals($expect, $actual[0]);

        //WHERE float
        $data = new _ModelData();
        $data->f_data = -20.02;
        $actual = $this->class->select($data, $order);

        $expect = new _modelData();
        $expect->fromArray($dataset[0]);

        $this->assertEquals($expect, $actual[0]);

        //WHERE double
        $data = new _ModelData();
        $data->d_data = -30.03;
        $actual = $this->class->select($data, $order);

        $expect = new _modelData();
        $expect->fromArray($dataset[0]);

        $this->assertEquals($expect, $actual[0]);

        //WHERE string
        $data = new _ModelData();
        $data->s_data = '漢字';
        $actual = $this->class->select($data, $order);

        $expect = new _modelData();
        $expect->fromArray($dataset[0]);

        $this->assertEquals($expect, $actual[0]);

        //WHERE DateTime
        $data = new _ModelData();
        $data->t_data = '20141201';
        $actual = $this->class->select($data, $order);

        $expect = new _modelData();
        $expect->fromArray($dataset[1]);

        $this->assertEquals($expect, $actual[0]);
    }

    /**
    */
    public function testExceptionInsertNotArray()
    {
//      $this->markTestIncomplete();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('not Array');
        $ar = 'STRING';
        $this->class->insert($ar);
    }

    /**
    */
    public function testExceptionInsertNotTraversable()
    {
//      $this->markTestIncomplete();

        $ar = new StdClass();
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('not Array');
        $this->class->insert($ar);
    }

    /**
    */
    public function testExceptionInsertNotDataType()
    {
//      $this->markTestIncomplete();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('data type different');
        $ar = array(new StdClass());
        $this->class->insert($ar);
    }

    public function testSuccessInsert()
    {
//      $this->markTestIncomplete();

        //full
        unset($ar);
        $insert = new _ModelData();
        $insert->b_data = true;
        $insert->i_data = 100;
        $insert->f_data = -200.002;
        $insert->d_data = -300.003;
        $insert->s_data = '追加データ';
        $insert->t_data = '20141217';
        $ar[] = $insert;
        $this->class->insert($ar);

        $select = new _modelData();
        $select->i_data = 100;
            $actual = $this->class->select($select);

        $this->assertEquals($insert->toArray(), $actual[0]->toArray());

        //part
        unset($ar);
        $insert = new _ModelData();
        $insert->b_data = false;
        $insert->i_data = 110;
        $insert->s_data = '複数追加データ';
        $ar[] = $insert;
        $this->class->insert($ar);

        $select = new _modelData();
        $select->i_data = 110;
            $actual = $this->class->select($select);

        $expect = array_merge(
            ArrayUtil::mergeKey($insert->toArray(), $insert->getInfo()),
            $insert->toArray()
        );
        $expect['b_data'] = false;

        $this->assertEquals($expect, $actual[0]->toArray());

        //multi(other column)
        unset($ar);
        $insert = new _ModelData();
        $insert->b_data = false;
        $insert->i_data = 120;
        $insert->f_data = -220.0;
        $insert->d_data = -320.0;
        $insert->s_data = '複数追加データ2';
        $insert->t_data = '20141212 123456';
        $ar[] = $insert;

        $insert = new _ModelData();
        $insert->b_data = false;
        $insert->i_data = 130;
        $insert->f_data = -230.0;
        $insert->d_data = -330.0;
        $insert->s_data = '複数追加データ3';
        $insert->t_data = '20141213 123456';
        $ar[] = $insert;

        $this->class->insert($ar);

        foreach ($ar as $obj) {
            $select = new _modelData();
            $select->i_data = $obj->i_data;
            $actual = $this->class->select($select);

            $expect = array_merge(
                ArrayUtil::mergeKey($obj->toArray(), $obj->getInfo()),
                $actual[0]->toArray()
            );
            $expect['b_data'] = (is_null($expect['b_data'])) ?   false : $expect['b_data'];

            $this->assertEquals($expect, $actual[0]->toArray());
        }

        //multi(another column)
        unset($ar);
        $insert = new _ModelData();
        $insert->b_data = false;
        $insert->i_data = 140;
        $insert->f_data = -240.0;
        $insert->d_data = -340.0;
        $insert->s_data = '複数追加データ4';
        $insert->t_data = '20141214 123456';
        $ar[] = $insert;

        $insert = new _ModelData();
        $insert->b_data = false;
        $insert->i_data = 150;
        $ar[] = $insert;

        $insert = new _ModelData();
        $insert->b_data = false;
        $insert->i_data = 160;
        $insert->s_data = '複数追加データ6';
        $insert->t_data = '20141216 123456';
        $ar[] = $insert;

        $this->class->insert($ar);

        foreach ($ar as $obj) {
            $select = new _modelData();
            $select->i_data = $obj->i_data;
            $actual = $this->class->select($select);

            $expect = array_merge(
                ArrayUtil::mergeKey($obj->toArray(), $obj->getInfo()),
                $actual[0]->toArray()
            );
            $expect['b_data'] = (is_null($expect['b_data'])) ?   false : $expect['b_data'];

            $this->assertEquals($expect, $actual[0]->toArray());
        }
    }

    /**
    */
    public function testExceptionUpdateNotArray()
    {
//      $this->markTestIncomplete();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('not Array');
        $ar = 'STRING';
        $this->class->update($ar);
    }

    /**
    */
    public function testExceptionUpdateNotTraversable()
    {
//      $this->markTestIncomplete();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('not Array');
        $ar = new StdClass();
        $this->class->update($ar);
    }

    /**
    */
    public function testExceptionUpdateNotInnerArray()
    {
//      $this->markTestIncomplete();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('nner array not Array');
        $update = new _ModelData();
        $ar = array(array($update));
        $this->class->update($ar);
    }

    /**
    */
    public function testExceptionUpdateNotInnerArray2()
    {
//      $this->markTestIncomplete();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('nner array not Array');
        $update = new _ModelData();
        $ar = array(array($update, $update, $update));
        $this->class->update($ar);
    }

    /**
    */
    public function testExceptionUpdateNotDataType()
    {
//      $this->markTestIncomplete();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('data type different');
        $update = new _ModelData();
        $std = new StdClass();
        $ar = array(array($std, $update));
        $this->class->update($ar);
    }

    /**
    */
    public function testExceptionUpdateRollback()
    {
//      $this->markTestIncomplete();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('data type different');
        $empty = new _ModelData();
        $update = clone $empty;
        $where = clone $empty;

        $update->s_data = 'CHANGE STR';
        $where->i_data = 10;
        $ar[0] = array($update, $where);
        $std = new StdClass();
        $ar[1] = array($update, $std);
        $this->class->update($ar);
    }

    public function testSuccessUpdate()
    {
//      $this->markTestIncomplete();

        $yaml = Yaml::parse(file_get_contents($this->file));
        $dataset = ArrayUtil::orderBy($yaml[$this->tablename], array('i_data'));

        //bool = false対策
        array_walk_recursive($dataset, function (&$val, $key) {
            if (($key == 'b_data') && ($val === 'false')) {
                $val = false;
            }
        });

        //1 data
        $empty = new _ModelData();
        $update = clone $empty;
        $where = clone $empty;

        $update->s_data = 'CHANGE STR';
        $where->i_data = 10;
        $ar = array(array($update, $where));
        $this->class->update($ar);

        $actual = $this->class->select($empty, 'i_data');
        $data = $dataset;
        $data[1]['s_data'] = 'CHANGE STR';

        for (
            $i = 0, $length = count($data);
            $i < $length;
            $i++
        ) {
            $expect = clone $empty;
            $expect->fromArray($data[$i]);
            $this->assertEquals($expect, $actual[$i]);
        }

        //2 data
        $empty = new _ModelData();
        $update = clone $empty;
        $where = clone $empty;

        $update->t_data = new DateTime('20150201');
        $where->f_data = 20.02;
        $ar[0] = array($update, $where);

        $update = clone $empty;
        $where = clone $empty;

        $update->b_data = true;
        $where->s_data = '漢字';
        $ar[1] = array($update, $where);

        $this->class->update($ar);

        $actual = $this->class->select($empty, 'i_data');
        $data[0]['b_data'] = true;
        $data[1]['t_data'] = '20150201';

        for (
            $i = 0, $length = count($data);
            $i < $length;
            $i++
        ) {
            $expect = clone $empty;
            $expect->fromArray($data[$i]);
            $this->assertEquals($expect, $actual[$i]);
        }
    }

    /**
    */
    public function testExceptionDeleteNotArray()
    {
//      $this->markTestIncomplete();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('not Array');
        $ar = 'STRING';
        $this->class->delete($ar);
    }

    /**
    */
    public function testExceptionDeleteNotTraversable()
    {
//      $this->markTestIncomplete();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('not Array');
        $ar = new StdClass();
        $this->class->delete($ar);
    }

    /**
    */
    public function testExceptionDeleteNotDataType()
    {
//      $this->markTestIncomplete();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('data type different');
        $ar = array(new StdClass());
        $this->class->delete($ar);
    }

    public function testSuccessDelete1()
    {
//      $this->markTestIncomplete();

        $yaml = Yaml::parse(file_get_contents($this->file));
        $dataset = ArrayUtil::orderBy($yaml[$this->tablename], array('i_data'));
        //boolean falseが""を付けないとDBフィクスチャが作れない為、bool型に戻す
        foreach ($dataset as &$list) {
            $list['b_data'] = (strtolower((string)$list['b_data']) == 'false') ?     false : true;
        }
        unset($list);

        //1 data
        $empty = new _ModelData();
        $where = clone $empty;
        $where->s_data = '漢字';
        $this->class->delete(array($where));

        $actual = $this->class->select($empty, 'i_data');
        $expect = clone $empty;
        $expect->fromArray($dataset[1]);

        $this->assertEquals($expect, $actual[0]);
    }

    public function testSuccessDelete2()
    {
//      $this->markTestIncomplete();

        $yaml = Yaml::parse(file_get_contents($this->file));
        $dataset = ArrayUtil::orderBy($yaml[$this->tablename], array('i_data'));
        //boolean falseが""を付けないとDBフィクスチャが作れない為、bool型に戻す
        foreach ($dataset as &$list) {
            $list['b_data'] = (strtolower((string)$list['b_data']) == 'false') ?     false : true;
        }
        unset($list);

        //2 data
        $empty = new _ModelData();
        $where = clone $empty;
        $where->s_data = '漢字';
        $ar[0] = $where;

        $where = clone $empty;
        $where->i_data = 10;
        $ar[1] = $where;

        $this->class->delete($ar);

        $actual = $this->class->select($empty, 'i_data');

        $this->assertEquals(array(), $actual);
    }

    public function testSuccessDelete3()
    {
//      $this->markTestIncomplete();

        $yaml = Yaml::parse(file_get_contents($this->file));
        $dataset = ArrayUtil::orderBy($yaml[$this->tablename], array('i_data'));
        //boolean falseが""を付けないとDBフィクスチャが作れない為、bool型に戻す
        foreach ($dataset as &$list) {
            $list['b_data'] = (strtolower((string)$list['b_data']) == 'false') ?     false : true;
        }
        unset($list);

        //1 data 2 where
        $empty = new _ModelData();
        $where = clone $empty;
        $where->s_data = '漢字';
        $where->i_data = -10;
        $ar[0] = $where;

        $this->class->delete($ar);

        $actual = $this->class->select($empty, 'i_data');
        $expect = clone $empty;
        $expect->fromArray($dataset[1]);

        $this->assertEquals($expect, $actual[0]);
    }

    /**
    *   @test
    */
    public function copyRecord()
    {
//      $this->markTestIncomplete();

        $where = new _ModelDbData();
        $where->i_data = 10;
        $replace = new _ModelDbData();
        $replace->i_data = 110;
        $replace->s_data = 'COPY';

        $this->class->copyRecord($where, $replace);

        $actual = $this->getConnection()->createQueryTable(
            $this->tablename,
            "SELECT * FROM {$this->tablename} WHERE i_data = 110"
        )->getRow(0);

        $expect = [
            // 'b_data' => true
            'b_data' => '1'
            , "i_data" => 110
            , "f_data" => 20.02
            , "d_data" => 30.03
            , "s_data" => 'COPY'
            // , "t_data" => '2014-12-01 00:00:00+09'
            , "t_data" => '20141201 000000'
        ];

        $this->assertEquals($expect, $actual);
    }

    /**
    *   @test
    *   @depends copyRecord
    */
    public function groupBy()
    {
//      $this->markTestIncomplete();

        //テスト用データ追加
        $where = new _ModelDbData();
        $where->i_data = 10;
        $replace = new _ModelDbData();
        $replace->i_data = 110;
        $replace->d_data = 200.0;
        $this->class->copyRecord($where, $replace);

        $where = new _ModelDbData();
        $where->f_data = 20.02;
        $group = 'f_data';
        $select = 'SUM(i_data) AS i_data, MAX(d_data) AS d_data';
        $result = $this->class->groupBy($select, $where, $group);

        $expect = array('i_data' => 120, 'd_data' => 200.0);
        $obj = $result[0];
        $actual = $obj->toArray();

        $this->assertEquals($expect, $actual);
    }

    /**
    *   @test
    */
    public function entityName()
    {
//      $this->markTestIncomplete();

        $this->assertEquals(
            'Concerto\test\standard\_ModelDbData',
            $this->class->entityName()
        );

        $modelDb = new ModelDb(static::$pdo);

        $this->assertEquals(
            'Concerto\standard\ModelDbData',
            $modelDb->entityName()
        );

        $modelDb = new DATABASE\CyubanInf(static::$pdo);

        $this->assertEquals(
            'Concerto\database\CyubanInfData',
            $modelDb->entityName()
        );
    }

    /**
    *   @test
    *
    */
    public function isValidOrderClause()
    {
//      $this->markTestIncomplete();

        $actual = Closure::bind(
            function () {
                $data = new _ModelData();
                $order = " user, PASS DESC, 　naMe ASC, adr ";
                return $this->isValidOrderClause($data, $order);
            },
            $this->class,
            'Concerto\test\standard\_ModelDb'
        )->__invoke();

        $this->assertEquals(false, $actual);


        $actual = Closure::bind(
            function () {
                $data = new _ModelData();
                $order = " i_data, S_DATA DESC, 　f_data ASC, b_data ";
                return $this->isValidOrderClause($data, $order);
            },
            $this->class,
            'Concerto\test\standard\_ModelDb'
        )->__invoke();

        $this->assertEquals(true, $actual);
    }

    /**
    *   @test
    *
    */
    public function isValidAggClause()
    {
//      $this->markTestIncomplete();

        $actual = Closure::bind(
            function () {
                $data = new _ModelData();
                $order = " user, PASS DESC, 　naMe ASC, adr ";
                return $this->isValidAggClause($data, $order);
            },
            $this->class,
            'Concerto\test\standard\_ModelDb'
        )->__invoke();

        $this->assertEquals(false, $actual);


        $actual = Closure::bind(
            function () {
                $data = new _ModelData();
                $order = " i_data, S_DATA DESC, 　f_data ASC, b_data ";
                return $this->isValidAggClause($data, $order);
            },
            $this->class,
            'Concerto\test\standard\_ModelDb'
        )->__invoke();

        $this->assertEquals(true, $actual);


        $actual = Closure::bind(
            function () {
                $data = new _ModelData();
                $order = "rank() OVER (PARTITION by i_data ORDER BY s_data DESC) AS i_data ";
                return $this->isValidAggClause($data, $order);
            },
            $this->class,
            'Concerto\test\standard\_ModelDb'
        )->__invoke();

        $this->assertEquals(true, $actual);
    }

    /**
    *   @test
    */
    public function isValidClause()
    {
//      $this->markTestIncomplete();

        $template = new _ModelData();

        //order_clause
        $haystack = $this->getPrivateProperty($this->class, 'order_clause');
        $clause = 'i_data DESC';
        $args = array($template, $clause, $haystack);

        $actual = $this->callPrivateMethod($this->class, 'isValidClause', $args);
        $this->assertEquals(true, $actual);

        $clause = 'i_data DESC, s_data, b_data ASC';
        $args = array($template, $clause, $haystack);

        $actual = $this->callPrivateMethod($this->class, 'isValidClause', $args);
        $this->assertEquals(true, $actual);
    }

    /**
    *   @test
    */
    public function testTruncate()
    {
//      $this->markTestIncomplete();

        $this->assertEquals(2, $this->getConnection()->getRowCount($this->tablename));
        $this->class->truncate();
        $this->assertEquals(0, $this->getConnection()->getRowCount($this->tablename));
    }

    /**
    *   @test
    */
    public function isValidCopyParams()
    {
//      $this->markTestIncomplete();

        $data = array(
            //'format' => 'text',
            'delimiter' => "\t",
            'null' => '\NN',
            //'quote' => '@',
            //'escape' => '$',
            //'header' => true,
            //'encoding' => 'SJIS'
        );

        $actual = $this->callPrivateMethod($this->class, 'isValidCopyParams', array($data));
        $this->assertEquals(true, $actual);

        $data = array(
            //'format' => 'text',
            'delimiter' => '\t',    //1byte文字でない
            'null' => '\NN',
            //'quote' => '@',
            //'escape' => '$',
            //'header' => true,
            //'encoding' => 'SJIS'
        );

        $actual = $this->callPrivateMethod($this->class, 'isValidCopyParams', array($data));
        $this->assertEquals(false, $actual);
    }

    /**
    *   @test
    */
    public function testExceptionImport1()
    {
//      $this->markTestIncomplete();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('file not found');
        $this->class->import('not_found_file');
    }

    /**
    *   @test
    */
    public function testExceptionImport2()
    {
//      $this->markTestIncomplete();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('different EXT type');
        $file = __DIR__ . '\\data\\modelDb\\_modeldb.yml';
        $this->class->import($file);
    }

    /**
    *   @test
    */
    public function testExceptionImport3()
    {
//      $this->markTestIncomplete();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('invalid parameter');
        $file = __DIR__ . '\\data\\modelDb\\import.csv';
        $params = array(
            'delimiter' => '\t',    //1byte文字でない
        );
        $this->class->import($file, $params);
    }

    /**
    *   @test
    */
    public function import()
    {
//      $this->markTestIncomplete();

        $this->class->truncate();
        $file = __DIR__ . '\\data\\modelDb\\import.csv';
        $this->class->import($file);
        $this->assertEquals(3, $this->getConnection()->getRowCount($this->tablename));

        $this->class->truncate();
        $file = __DIR__ . '\\data\\modelDb\\import.csv';
        $params = array('null' => null);
        $this->class->import($file, $params);
        $this->assertEquals(3, $this->getConnection()->getRowCount($this->tablename));
    }

    /**
    *   @test
    */
    public function errorInfoToMessage()
    {
//      $this->markTestIncomplete();

        $pdo = static::$pdo;

        $actual = $this->callPrivateMethod($this->class, 'errorInfoToMessage', $args = array($pdo));
        $expect = '00000//';
        $this->assertEquals($expect, $actual);

        $actual = $this->callPrivateMethod($this->class, 'errorInfoToMessage', $args = array(null));
        $expect = '';
        $this->assertEquals($expect, $actual);

        $stmt = $pdo->prepare('select 1');

        $actual = $this->callPrivateMethod($this->class, 'errorInfoToMessage', $args = array($stmt));
        $expect = '//';
        $this->assertEquals($expect, $actual);

        try {
            $stmt = $pdo->prepare('select 1');
            $stmt->bindValue(':x', null);
        } catch (Exception $e) {
        }

        $actual = $this->callPrivateMethod($this->class, 'errorInfoToMessage', $args = array($stmt));
        $expect = 'HY093//:x';
        $this->assertEquals($expect, $actual);
    }

    /**
    *   @test
    */
    public function getSchema()
    {
//      $this->markTestIncomplete();

        $this->assertEquals('test._modeldb', $this->class ->getSchema());
    }

    /**
    *   @test
    */
    public function upsert()
    {
//      $this->markTestIncomplete();

        $data = new _ModelData();
        $data->b_data = true;
        $data->i_data = 12;
        $data->f_data = 3.14;
        $data->d_data = 9.99;
        $data->s_data = 'abc';
        $data->t_data = new DateTime('2019-10-01 123456');

        $where = new _ModelData();
        $where->i_data = 12;

        //INSERT
        $db = new _ModelDbUpsert(static::$pdo);
        $db->upsert($data, $where);

        $result = $db->select($where);
        $this->assertEquals(1, count($result));
        $this->assertEquals($data->toArray(), ($result[0])->toArray());

        //UPDATE
        $data->s_data = 'ABC';
        $db = new _ModelDbUpsert(static::$pdo);
        $db->upsert($data, $where);

        $result = $db->select($where);
        $this->assertEquals(1, count($result));
        $this->assertEquals($data->toArray(), ($result[0])->toArray());
    }

    /**
    *   @test
    */
    public function createModel()
    {
     // $this->markTestIncomplete();

        $obj = new _ModelDbUpsert(static::$pdo);
        $expect = $obj->createModel();
        $this->assertEquals(true, $expect instanceof _ModelDbUpsertData);
    }
}
