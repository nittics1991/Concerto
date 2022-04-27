<?php

declare(strict_types=1);

namespace test\Concerto\standard;

use test\Concerto\{
    ConcertoTestCase,
    DatabaseTestTrait,
};
use Closure;
use DateTime;
use Exception;
use InvalidArgumentException;
use PDO;
use RuntimeException;
use StdClass;
use Concerto\standard\ArrayUtil;
use Concerto\standard\ModelData;
use Concerto\standard\ModelDb;
use Symfony\Component\Yaml\Yaml;
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
    protected static $schema = [
        "b_data" => parent::BOOLEAN
        , "i_data" => parent::INTEGER
        , "f_data" => parent::FLOAT
        , "d_data" => parent::DOUBLE
        , "s_data" => parent::STRING
        , "t_data" => parent::DATETIME
    ];
}

//copyRecordで使用
class _ModelDbData extends ModelData
{
    protected static $schema = [
        "b_data" => parent::BOOLEAN
        , "i_data" => parent::INTEGER
        , "f_data" => parent::FLOAT
        , "d_data" => parent::DOUBLE
        , "s_data" => parent::STRING
        , "t_data" => parent::DATETIME
    ];
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


class ModelDbTest extends ConcertoTestCase
{
    use DatabaseTestTrait;

    private $obj;
    private $file;
    private $tablename = 'test._modeldb';

    protected function setUp(): void
    {
        global $DB_DSN;
        global $DB_USER;
        global $DB_PASSWD;
        global $DB_DBNAME;

        if (
            (
                extension_loaded("pdo-pgsql") ||
                extension_loaded("pgsql")
             ) &&
            !preg_match('/543[0,4,6]/', $GLOBALS['DB_DSN'])
        ) {
            throw new RuntimeException(
                "PostgreSQL DNS ERROR"
            );
        }

        $this->pdo = new PDO(
            $DB_DSN,
            $DB_USER,
            $DB_PASSWD,
            [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ],
        );

        $this->obj = new _ModelDb($this->pdo);

        $this->truncateTable(
            'test._modeldb',
            $this->pdo,
        );

        $this->file =
            __DIR__ . DIRECTORY_SEPARATOR .
            implode(
                DIRECTORY_SEPARATOR,
                ['data', 'modelDb','_modeldb.yml'],
            );

        $dataset = $this->getDataSet();

        $this->importData(
            $this->tablename,
            $dataset['test._modeldb'],
            $this->pdo,
        );
    }

    protected function getDataSet()
    {
        $dataset = Yaml::parseFile($this->file);
        return $dataset;
    }

    public function testRowCount()
    {
//      $this->markTestIncomplete();

        $this->assertEquals(2, $this->rowCount($this->tablename));
    }

    public function testSelect()
    {
//      $this->markTestIncomplete();

        $yaml = Yaml::parseFile($this->file);
        $dataset = ArrayUtil::orderBy($yaml[$this->tablename], ['i_data']);


        //bool = false対策
        array_walk_recursive($dataset, function (&$val, $key) {
            if (($key == 'b_data') && ($val === 'false')) {
                $val = false;
            }
        });

        //All
        $data = new _ModelData();
        $order = 'i_data';
        $actual = $this->obj->select($data, $order);

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
        $actual = $this->obj->select($data, $order);

        $expect = new _modelData();
        $expect->fromArray($dataset[1]);

        $this->assertEquals($expect, $actual[0]);

        //WHERE integer
        $data = new _ModelData();
        $data->i_data = -10;
        $actual = $this->obj->select($data, $order);

        $expect = new _modelData();
        $expect->fromArray($dataset[0]);

        $this->assertEquals($expect, $actual[0]);

        //WHERE float
        $data = new _ModelData();
        $data->f_data = -20.02;
        $actual = $this->obj->select($data, $order);

        $expect = new _modelData();
        $expect->fromArray($dataset[0]);

        $this->assertEquals($expect, $actual[0]);

        //WHERE double
        $data = new _ModelData();
        $data->d_data = -30.03;
        $actual = $this->obj->select($data, $order);

        $expect = new _modelData();
        $expect->fromArray($dataset[0]);

        $this->assertEquals($expect, $actual[0]);

        //WHERE string
        $data = new _ModelData();
        $data->s_data = '漢字';
        $actual = $this->obj->select($data, $order);

        $expect = new _modelData();
        $expect->fromArray($dataset[0]);

        $this->assertEquals($expect, $actual[0]);

        //WHERE DateTime
        $data = new _ModelData();
        $data->t_data = '20141201';
        $actual = $this->obj->select($data, $order);

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
        $this->obj->insert($ar);
    }

    /**
    */
    public function testExceptionInsertNotTraversable()
    {
//      $this->markTestIncomplete();

        $ar = new StdClass();
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('not Array');
        $this->obj->insert($ar);
    }

    /**
    */
    public function testExceptionInsertNotDataType()
    {
//      $this->markTestIncomplete();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('data type different');
        $ar = [new StdClass()];
        $this->obj->insert($ar);
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
        $this->obj->insert($ar);

        $select = new _modelData();
        $select->i_data = 100;
            $actual = $this->obj->select($select);

        $this->assertEquals($insert->toArray(), $actual[0]->toArray());

        //part
        unset($ar);
        $insert = new _ModelData();
        $insert->b_data = false;
        $insert->i_data = 110;
        $insert->s_data = '複数追加データ';
        $ar[] = $insert;
        $this->obj->insert($ar);

        $select = new _modelData();
        $select->i_data = 110;
            $actual = $this->obj->select($select);

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

        $this->obj->insert($ar);

        foreach ($ar as $obj) {
            $select = new _modelData();
            $select->i_data = $obj->i_data;
            $actual = $this->obj->select($select);

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

        $this->obj->insert($ar);

        foreach ($ar as $obj) {
            $select = new _modelData();
            $select->i_data = $obj->i_data;
            $actual = $this->obj->select($select);

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
        $this->obj->update($ar);
    }

    /**
    */
    public function testExceptionUpdateNotTraversable()
    {
//      $this->markTestIncomplete();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('not Array');
        $ar = new StdClass();
        $this->obj->update($ar);
    }

    /**
    */
    public function testExceptionUpdateNotInnerArray()
    {
//      $this->markTestIncomplete();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('nner array not Array');
        $update = new _ModelData();
        $ar = [[$update]];
        $this->obj->update($ar);
    }

    /**
    */
    public function testExceptionUpdateNotInnerArray2()
    {
//      $this->markTestIncomplete();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('nner array not Array');
        $update = new _ModelData();
        $ar = [[$update, $update, $update]];
        $this->obj->update($ar);
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
        $ar = [[$std, $update]];
        $this->obj->update($ar);
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
        $ar[0] = [$update, $where];
        $std = new StdClass();
        $ar[1] = [$update, $std];
        $this->obj->update($ar);
    }

    public function testSuccessUpdate()
    {
//      $this->markTestIncomplete();

        $yaml = Yaml::parseFile($this->file);
        $dataset = ArrayUtil::orderBy($yaml[$this->tablename], ['i_data']);

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
        $ar = [[$update, $where]];
        $this->obj->update($ar);

        $actual = $this->obj->select($empty, 'i_data');
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
        $ar[0] = [$update, $where];

        $update = clone $empty;
        $where = clone $empty;

        $update->b_data = true;
        $where->s_data = '漢字';
        $ar[1] = [$update, $where];

        $this->obj->update($ar);

        $actual = $this->obj->select($empty, 'i_data');
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
        $this->obj->delete($ar);
    }

    /**
    */
    public function testExceptionDeleteNotTraversable()
    {
//      $this->markTestIncomplete();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('not Array');
        $ar = new StdClass();
        $this->obj->delete($ar);
    }

    /**
    */
    public function testExceptionDeleteNotDataType()
    {
//      $this->markTestIncomplete();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('data type different');
        $ar = [new StdClass()];
        $this->obj->delete($ar);
    }

    public function testSuccessDelete1()
    {
//      $this->markTestIncomplete();

        $yaml = Yaml::parseFile($this->file);
        $dataset = ArrayUtil::orderBy($yaml[$this->tablename], ['i_data']);
        //boolean falseが""を付けないとDBフィクスチャが作れない為、bool型に戻す
        foreach ($dataset as &$list) {
            $list['b_data'] = (strtolower((string)$list['b_data']) == 'false') ?     false : true;
        }
        unset($list);

        //1 data
        $empty = new _ModelData();
        $where = clone $empty;
        $where->s_data = '漢字';
        $this->obj->delete([$where]);

        $actual = $this->obj->select($empty, 'i_data');
        $expect = clone $empty;
        $expect->fromArray($dataset[1]);

        $this->assertEquals($expect, $actual[0]);
    }

    public function testSuccessDelete2()
    {
//      $this->markTestIncomplete();

        $yaml = Yaml::parseFile($this->file);
        $dataset = ArrayUtil::orderBy($yaml[$this->tablename], ['i_data']);
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

        $this->obj->delete($ar);

        $actual = $this->obj->select($empty, 'i_data');

        $this->assertEquals([], $actual);
    }

    public function testSuccessDelete3()
    {
//      $this->markTestIncomplete();

        $yaml = Yaml::parseFile($this->file);
        $dataset = ArrayUtil::orderBy($yaml[$this->tablename], ['i_data']);
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

        $this->obj->delete($ar);

        $actual = $this->obj->select($empty, 'i_data');
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

        $this->obj->copyRecord($where, $replace);

        $stmt = $this->executeQuery(
            "SELECT * FROM {$this->tablename} WHERE i_data = 110",
            [],
            $this->pdo,
        );

        $actual = (array)$stmt->fetchAll();

        $expect = [
            [
                // 'b_data' => true
                'b_data' => '1'
                , "i_data" => 110
                , "f_data" => 20.02
                , "d_data" => 30.03
                , "s_data" => 'COPY'
                // , "t_data" => '2014-12-01 00:00:00+09'
                , "t_data" => '20141201 000000'
            ],
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
        $this->obj->copyRecord($where, $replace);

        $where = new _ModelDbData();
        $where->f_data = 20.02;
        $group = 'f_data';
        $select = 'SUM(i_data) AS i_data, MAX(d_data) AS d_data';
        $result = $this->obj->groupBy($select, $where, $group);

        $expect = ['i_data' => 120, 'd_data' => 200.0];
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
            'test\Concerto\standard\_ModelDbData',
            $this->obj->entityName()
        );

        $modelDb = new ModelDb($this->pdo);

        $this->assertEquals(
            'Concerto\standard\ModelDbData',
            $modelDb->entityName()
        );

        $modelDb = new DATABASE\CyubanInf($this->pdo);

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
            $this->obj,
            'test\Concerto\standard\_ModelDb'
        )->__invoke();

        $this->assertEquals(false, $actual);


        $actual = Closure::bind(
            function () {
                $data = new _ModelData();
                $order = " i_data, S_DATA DESC, 　f_data ASC, b_data ";
                return $this->isValidOrderClause($data, $order);
            },
            $this->obj,
            'test\Concerto\standard\_ModelDb'
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
            $this->obj,
            'test\Concerto\standard\_ModelDb'
        )->__invoke();

        $this->assertEquals(false, $actual);


        $actual = Closure::bind(
            function () {
                $data = new _ModelData();
                $order = " i_data, S_DATA DESC, 　f_data ASC, b_data ";
                return $this->isValidAggClause($data, $order);
            },
            $this->obj,
            'test\Concerto\standard\_ModelDb'
        )->__invoke();

        $this->assertEquals(true, $actual);


        $actual = Closure::bind(
            function () {
                $data = new _ModelData();
                $order = "rank() OVER (PARTITION by i_data ORDER BY s_data DESC) AS i_data ";
                return $this->isValidAggClause($data, $order);
            },
            $this->obj,
            'test\Concerto\standard\_ModelDb'
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
        $haystack = $this->getPrivateProperty($this->obj, 'order_clause');
        $clause = 'i_data DESC';
        $args = [$template, $clause, $haystack];

        $actual = $this->callPrivateMethod($this->obj, 'isValidClause', $args);
        $this->assertEquals(true, $actual);

        $clause = 'i_data DESC, s_data, b_data ASC';
        $args = [$template, $clause, $haystack];

        $actual = $this->callPrivateMethod($this->obj, 'isValidClause', $args);
        $this->assertEquals(true, $actual);
    }

    /**
    *   @test
    */
    public function testTruncate()
    {
//      $this->markTestIncomplete();

        $this->assertEquals(2, $this->rowCount($this->tablename));
        $this->obj->truncate();
        $this->assertEquals(0, $this->rowCount($this->tablename));
    }

    /**
    *   @test
    */
    public function isValidCopyParams()
    {
//      $this->markTestIncomplete();

        $data = [
            //'format' => 'text',
            'delimiter' => "\t",
            'null' => '\NN',
            //'quote' => '@',
            //'escape' => '$',
            //'header' => true,
            //'encoding' => 'SJIS'
        ];

        $actual = $this->callPrivateMethod($this->obj, 'isValidCopyParams', [$data]);
        $this->assertEquals(true, $actual);

        $data = [
            //'format' => 'text',
            'delimiter' => '\t',    //1byte文字でない
            'null' => '\NN',
            //'quote' => '@',
            //'escape' => '$',
            //'header' => true,
            //'encoding' => 'SJIS'
        ];

        $actual = $this->callPrivateMethod($this->obj, 'isValidCopyParams', [$data]);
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
        $this->obj->import('not_found_file');
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
        $this->obj->import($file);
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
        $params = [
            'delimiter' => '\t',    //1byte文字でない
        ];
        $this->obj->import($file, $params);
    }

    /**
    *   @test
    */
    public function import()
    {
//      $this->markTestIncomplete();

        $this->obj->truncate();
        $file = __DIR__ . '\\data\\modelDb\\import.csv';
        $this->obj->import($file);
        $this->assertEquals(3, $this->rowCount($this->tablename));

        $this->obj->truncate();
        $file = __DIR__ . '\\data\\modelDb\\import.csv';
        $params = ['null' => null];
        $this->obj->import($file, $params);
        $this->assertEquals(3, $this->rowCount($this->tablename));
    }

    /**
    *   @test
    */
    public function errorInfoToMessage()
    {
//      $this->markTestIncomplete();

        $pdo = $this->pdo;

        $actual = $this->callPrivateMethod($this->obj, 'errorInfoToMessage', $args = [$pdo]);
        $expect = '00000//';
        $this->assertEquals($expect, $actual);

        $actual = $this->callPrivateMethod($this->obj, 'errorInfoToMessage', $args = [null]);
        $expect = '';
        $this->assertEquals($expect, $actual);

        $stmt = $pdo->prepare('select 1');

        $actual = $this->callPrivateMethod($this->obj, 'errorInfoToMessage', $args = [$stmt]);
        $expect = '//';
        $this->assertEquals($expect, $actual);

        try {
            $stmt = $pdo->prepare('select 1');
            $stmt->bindValue(':x', null);
        } catch (Exception $e) {
        }

        $actual = $this->callPrivateMethod($this->obj, 'errorInfoToMessage', $args = [$stmt]);
        $expect = 'HY093//:x';
        $this->assertEquals($expect, $actual);
    }

    /**
    *   @test
    */
    public function getSchema()
    {
//      $this->markTestIncomplete();

        $this->assertEquals('test._modeldb', $this->obj ->getSchema());
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
        $db = new _ModelDbUpsert($this->pdo);
        $db->upsert($data, $where);

        $result = $db->select($where);
        $this->assertEquals(1, count($result));
        $this->assertEquals($data->toArray(), ($result[0])->toArray());

        //UPDATE
        $data->s_data = 'ABC';
        $db = new _ModelDbUpsert($this->pdo);
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

        $obj = new _ModelDbUpsert($this->pdo);
        $expect = $obj->createModel();
        $this->assertEquals(true, $expect instanceof _ModelDbUpsertData);
    }

    /**
    *   @test
    */
    public function selectRow()
    {
     // $this->markTestIncomplete();

        $where = new _ModelData();
        $all_data = $this->obj->select(
            $where,
            'i_data',
        );

        $row_count = count($all_data);
        if ($row_count === 0) {
            throw new RuntimeException(
                "data empty"
            );
        }

        $this->assertEquals(
            $this->obj->selectRow(
                $where,
                'i_data',
            ),
            $all_data[0],
        );

        $this->assertEquals(
            $this->obj->selectRow(
                $where,
                'i_data',
                1,
            ),
            $all_data[1],
        );

        $this->assertEquals(
            $this->obj->selectRow(
                $where,
                'i_data',
                -1,
            ),
            $all_data[$row_count - 1],
        );
    }
}
