<?php

declare(strict_types=1);

namespace test\Concerto\sql\simpleTable;

use test\Concerto\ConcertoTestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use Concerto\sql\simpleTable\Sqlite;
use Concerto\standard\ModelData;
use Concerto\standard\ModelDb;
use PDO;

class SqliteTest extends ConcertoTestCase
{
    public $object;

    public function setUp(): void
    {
    }

    /**
    */
    #[Test]
    public function createTable()
    {
     $this->markTestIncomplete('--- phpunit11でmock動かない ---');

        $table_schema = 'test.table';
        $table_name = 'test_table';

        $table = $this->createMock(ModelDb::class);
        $table->method('getSchema')
            ->willReturn($table_schema)
        ;

        //phpunit ver11 Mockを見直してもエラー
        $columns = $this->getMockBuilder(ModelData::class)
            ->setMethods(null)
            ->getMock();
        ;

        $schema = [
            'prop_s' => 'string',
            'prop_b' => 'boolean',
            'prop_d' => 'double',
            'prop_i' => 'integer',
            'prop_f' => 'double',
            'prop_t' => 'datetime',
        ];

        $this->setPrivateProperty($columns, 'schema', $schema);

        $this->object = new Sqlite($table, $columns);

        $expect = "CREATE TABLE {$table_name} ('prop_s' TEXT, 'prop_b' TEXT, 'prop_d' REAL, 'prop_i' INTEGER, 'prop_f' REAL, 'prop_t' TEXT)";

        $this->assertEquals(
            $expect,
            $this->object->createTable()
        );

        ///////////////////////////////////////////////////////////////////////

        $dns = 'sqlite::memory:';
        $pdo = new PDO(
            $dns,
            null,
            null,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]
        );

        $sql = $this->object->createTable();
        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        //information_schema not support
        $sql = "
            SELECT * 
            FROM information_schema.columns 
            WHERE table_name = '{$table_name}' 
        ";

        $sql = "
            PRAGMA table_info({$table_name})
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $def = (array)$stmt->fetchAll();
        $columns = array_column($def, 'name');

        $this->assertEquals(array_keys($schema), $columns);
    }
}
