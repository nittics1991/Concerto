<?php

declare(strict_types=1);

namespace test\Concerto\standard;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use PDO;
use test\Concerto\{
    ConcertoTestCase,
    DatabaseTestTrait,
};
use Concerto\standard\ModelDbCacher;
use Concerto\standard\ModelData;
use Concerto\standard\ModelDb;


class _ModelDbCacher extends ModelDb
{
    protected string $schema = 'test._modeldbcacher';
}

class _ModelDataCacher extends ModelData
{
    protected static array $schema = [
        "b_data" => parent::BOOLEAN
        , "i_data" => parent::INTEGER
        , "f_data" => parent::FLOAT
        , "d_data" => parent::DOUBLE
        , "s_data" => parent::STRING
        , "t_data" => parent::DATETIME
    ];
}


///////////////////////////////////////////////////////////////////////////////////////////////////


class ModelDbCacherTest extends ConcertoTestCase
{
    use DatabaseTestTrait;

    private $obj;
    private $file;
    private $tablename = 'test._modeldbcacher';
    private $define_table;

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
        $this->obj = new ModelDb($this->pdo);

        $this->truncateTable(
            'test._modeldbcacher',
            $this->pdo,
        );

        $this->file =
            __DIR__ . DIRECTORY_SEPARATOR .
            implode(
                DIRECTORY_SEPARATOR,
                ['data', 'modelDbCacher','_modeldbcacher.php'],
            );

        $this->define_table = [
            "b_data" => 'TEXT',
            "i_data" => 'INTEGER',
            "f_data" => 'REAL',
            "d_data" => 'REAL',
            "s_data" => 'TEXT',
            "t_data" => 'TEXT',
        ];

        $dataset = $this->getDataSet();

        $this->importData(
            $this->tablename,
            $dataset['test._modeldbcacher'],
            $this->pdo,
        );
    }

    protected function getDataSet()
    {
        $dataset = require($this->file);
        return $dataset;
    }

    /**
    *
    */
    #[Test]
    public function checkSetupTable()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $dataset = $this->getDataSet();
        $datasetRowCount = count($dataset[$this->tablename]);

        $stmt = $this->executeQuery(
            'SELECT * FROM test._modeldbcacher WHERE 1 = 1',
            [],
            $this->pdo,
        );
        $tableData = $stmt->fetchAll();

        $this->assertEquals($datasetRowCount, count($tableData));
    }

    /**
    *
    */
    #[Test]
    public function addInsertData()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new ModelDbCacher($this->pdo);

        $data = new _ModelDataCacher();

        $data1 = clone $data;
        $data1->i_data = 10;
        $obj->addInsertData($data1);

        $data2 = clone $data;
        $data2->f_data = 10;
        $obj->addInsertData($data2);

        $expect = [$data1, $data2];

        $this->assertEquals(
            $expect,
            $this->getPrivateProperty($obj, 'inserts')
        );
    }

    /**
    *
    */
    #[Test]
    public function addUpdateData()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new ModelDbCacher($this->pdo);

        $data = new _ModelDataCacher();
        $where = clone $data;

        $where1 = clone $data;
        $where1->i_data = 10;
        $data1 = clone $data;
        $data1->s_data = "start";
        $obj->addUpdateData($data1, $where1);

        $where2 = clone $data;
        $where2->f_data = 10;
        $data2 = clone $data;
        $data2->s_data = "end";
        $obj->addUpdateData($data2, $where2);

        $expect = [[$data1, $where1], [$data2, $where2]];

        $this->assertEquals(
            $expect,
            $this->getPrivateProperty($obj, 'updates')
        );
    }

    /**
    *
    */
    #[Test]
    public function addDeleteData()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new ModelDbCacher($this->pdo);

        $data = new _ModelDataCacher();

        $where1 = clone $data;
        $where1->i_data = 10;
        $where1->s_data = "start";
        $obj->addInsertData($where1);

        $where2 = clone $data;
        $where2->f_data = 10;
        $where2->s_data = "end";
        $obj->addInsertData($where2);

        $expect = [$where1, $where2];

        $this->assertEquals(
            $expect,
            $this->getPrivateProperty($obj, 'inserts')
        );
    }

    /**
    *
    */
    #[Test]
    public function save1()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $data = new _ModelDataCacher();
        $where = clone $data;

        //insert
        $obj = new ModelDbCacher($this->pdo, new _ModelDbCacher($this->pdo));
        $data1 = clone $data;
        $data1->i_data = 100;
        $obj->addInsertData($data1);

        $data2 = clone $data;
        $data2->f_data = 200;
        $obj->addInsertData($data2);

        $obj->save();

        $stmt = $this->executeQuery(
            'SELECT * FROM test._modeldbcacher WHERE i_data=100 OR f_data = 200',
            [],
            $this->pdo,
        );
        $tableData = $stmt->fetchAll();

        $this->assertEquals(2, count($tableData));

        //update
        $obj = $obj->createCacher(new _ModelDbCacher($this->pdo));
        $where1 = clone $data;
        $where1->i_data = 100;
        $data1 = clone $data;
        $data1->s_data = "start";
        $obj->addUpdateData($data1, $where1);

        $where2 = clone $data;
        $where2->f_data = 100;
        $data2 = clone $data;
        $data2->s_data = "end";
        $obj->addUpdateData($data2, $where2);

        $obj->save();

        $stmt = $this->executeQuery(
            'SELECT * FROM test._modeldbcacher WHERE i_data=100',
            [],
            $this->pdo,
        );
        $tableData = $stmt->fetchAll();

        $this->assertEquals(1, count($tableData));
        $this->assertEquals('start', $tableData[0]['s_data']);

        //insert,update,delete
        $obj = $obj->createCacher(new _ModelDbCacher($this->pdo));

        $data1 = clone $data;
        $data1->i_data = 100;
        $data1->s_data = 'replace';
        $obj->addInsertData($data1);

        $where2 = clone $data;
        $where2->f_data = 200;
        $data2 = clone $data;
        $data2->s_data = "end";
        $obj->addUpdateData($data2, $where2);

        $where3 = clone $data;
        $where3->i_data = 100;
        $where3->s_data = 'start';
        $obj->addDeleteData($where3);

        $obj->save();

        $stmt = $this->executeQuery(
            'SELECT * FROM test._modeldbcacher WHERE 1 = 1',
            [],
            $this->pdo,
        );
        $tableData = $stmt->fetchAll();

        $this->assertEquals(4, count($tableData));

        $stmt = $this->executeQuery(
            'SELECT * FROM test._modeldbcacher WHERE i_data = 100',
            [],
            $this->pdo,
        );
        $tableData = $stmt->fetchAll();

        $this->assertEquals('replace', $tableData[0]['s_data']);

        $stmt = $this->executeQuery(
            'SELECT * FROM test._modeldbcacher WHERE f_data = 200',
            [],
            $this->pdo,
        );
        $tableData = $stmt->fetchAll();

        $this->assertEquals('end', $tableData[0]['s_data']);
    }
}
