<?php

declare(strict_types=1);

namespace test\Concerto\test;

use test\Concerto\AbstractSqliteTestCase;
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

class SqliteTestCaseTest extends AbstractSqliteTestCase
{
    private $file;

    protected function setUp(): void
    {
    }

    /**
    *   @test
    */
    public function testInitSqlitePdo()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->initPdo();
        $this->assertInstanceOf(PDO::class, $this->pdo);
    }

    /**
    *   @test
    */
    public function testSetupTable()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $pdo = $this->initPdo();
        $mailInf = new MailInf($pdo);
        $mailInfData = new MailInfData();

        $tablename = $this->setupTable($mailInf, $mailInfData);
        $this->assertEquals('public_mail_inf', $tablename);

        $sql = "
            PRAGMA table_info({$tablename})
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $def = (array)$stmt->fetchAll();

        $info = $mailInfData->getInfo();

        $columns = array_column($def, 'name');
        $this->assertEquals(array_keys($info), $columns);
    }
}
