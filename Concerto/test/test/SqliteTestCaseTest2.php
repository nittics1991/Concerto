<?php

declare(strict_types=1);

namespace Concerto\test\test;

use Concerto\test\abstractSqliteTestCase;
use PHPUnit\DbUnit\DataSet\YamlDataSet as PHPUnit_Extensions_Database_DataSet_YamlDataSet;
use Concerto\database\MailInf;
use Concerto\database\MailInfData;
use Concerto\sql\simpleTable\Sqlite;
use PDO;
use Concerto\standard\ModelDb;
use Concerto\standard\ModelData;

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

class SqliteTestCaseTest2 extends abstractSqliteTestCase
{
    public function getDataSet()
    {
        return new PHPUnit_Extensions_Database_DataSet_YamlDataSet(
            __DIR__ . '\\data\\_modeldb.yml'
        );
    }
    
    protected function setUp(): void
    {
        $pdo = $this->initPdo();
        $modelDb = new _ModelDb($pdo);
        $modelData = new _ModelData();
        $this->setupTable($modelDb, $modelData);
        parent::setup();
    }
    
    /**
    *   DB test方法確認
    *
    *   @test
    **/
    public function test1()
    {
//      $this->markTestIncomplete();
        
        $this->assertEquals(
            true,
            is_object($this->getConnection()->createDataSet()->getTable('_modeldb'))
        );
    }
}
