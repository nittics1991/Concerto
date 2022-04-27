<?php

declare(strict_types=1);

namespace test\Concerto\test;

use PDO;
use test\Concerto\AbstractSqliteTestCase;
use Concerto\standard\ModelDb;
use Concerto\standard\ModelData;
use Symfony\Component\Yaml\Yaml;

class _ModelDb extends ModelDb
{
    protected $schema = '_modeldb';
}

class _ModelData extends ModelData
{
    protected static $schema = [
        'b_data' => parent::BOOLEAN
        , "i_data" => parent::INTEGER
        , "f_data" => parent::FLOAT
        , "d_data" => parent::DOUBLE
        , "s_data" => parent::STRING
        , "t_data" => parent::DATETIME
    ];
}

/////////////////////////////////////////////////////////////////////////////

class SqliteTestCaseTest2 extends AbstractSqliteTestCase
{
    protected $tablename = '_modeldb';
    protected $modelDb;
    protected $modelData;

    protected function setUp(): void
    {
        $this->file =
            __DIR__ . DIRECTORY_SEPARATOR .
            implode(
                DIRECTORY_SEPARATOR,
                ['data', '_modeldb.yml'],
            );

        $this->initPdo();
        $this->modelDb = new _ModelDb($this->pdo);
        $this->modelData = new _ModelData();
        $this->setupTable($this->modelDb, $this->modelData);

        $dataset = $this->getDataSet();
        $this->importData(
            $this->tablename,
            $dataset[$this->tablename],
            $this->pdo,
        );
    }

    public function getDataSet()
    {
        return Yaml::parseFile($this->file);
    }

    /**
    *   DB test方法確認
    *
    *   @test
    */
    public function test1()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertTrue($this->existsTable($this->modelDb));

        $dataset = $this->getDataSet();
        $table = $this->fetchAllData($this->modelDb);

        $this->assertEquals(
            count($dataset[$this->tablename]),
            count($table),
        );
    }
}
