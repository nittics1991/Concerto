<?php

declare(strict_types=1);

namespace test\Concerto\test;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use PDO;
use PDOStatement;
use test\Concerto\{
    ConcertoTestCase,
    DatabaseTestTrait,
};

class DatabaseTestTraitObject
{
    use DatabaseTestTrait;

    public function createConnection(
        string $dns
    ): PDO {
        $this->pdo = new PDO($dns);
        return $this->pdo;
    }

    public function deleteConnection(): void
    {
        $this->pdo = null;
    }
}

////////////////////////////////////////////////////


class DatabaseTestTraitTest extends ConcertoTestCase
{
    protected PDO $pdo;
    protected string $tableName = 'prepareTableData';
    protected int $tableRowCount;

    protected function setUp(): void
    {
        $this->pdo = new PDO(
            'sqlite::memory:',
            null,
            null,
            [
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ],
        );
    }

    protected function prepareCreateTable()
    {
        $sql = "
            CREATE TABLE {$this->tableName} (
                i_data INTEGER,
                f_data REAL,
                s_data TEXT
            )
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
    }

    protected function prepareTableData()
    {
        $sql = "
            INSERT INTO prepareTableData (
                i_data,
                f_data,
                s_data
            ) VAlues
                (:i1, :f1, :s1),
                (:i2, :f2, :s2),
                (:i3, :f3, :s3)
        ";

        $stmt = $this->pdo->prepare($sql);

        $binds = [
            ':i1' => 1,
            ':f1' => 11.1,
            ':s1' => '1_1',
            ':i2' => 2,
            ':f2' => 21.1,
            ':s2' => '2_1',
            ':i3' => 3,
            ':f3' => 31.1,
            ':s3' => '3_1',
        ];
        $this->tableRowCount = count($binds) / 3;

        $position = 0;

        foreach ($binds as $key => $val) {
            $stmt->bindValue(
                $key,
                $val,
                $position % 3 === 0 ?
                    PDO::PARAM_INT :
                    PDO::PARAM_STR,
            );
            $position++;
        }

        $stmt->execute();
    }

    public static function executeQueryProvider()
    {
        return [
            //no bind & type
            [
                '
                    SELECT * FROM prepareTableData
                ',
                [],
                [
                    [
                        'i_data' => 1,
                        'f_data' => 11.1,
                        's_data' => '1_1',
                    ],
                    [
                        'i_data' => 2,
                        'f_data' => 21.1,
                        's_data' => '2_1',
                    ],
                    [
                        'i_data' => 3,
                        'f_data' => 31.1,
                        's_data' => '3_1',
                    ],
                ],
            ],
            //bind & type
            [
                '
                    SELECT * FROM prepareTableData
                        WHERE (
                            i_data = :i_data
                                AND f_data = :f_data
                                AND s_data = :s_data
                        ) IS NOT FALSE
                ',
                [
                    'i_data' => 2,
                    'f_data' => null,
                    's_data' => null,
                ],
                [
                    [
                        'i_data' => 2,
                        'f_data' => 21.1,
                        's_data' => '2_1',
                    ],
                ],
            ],
            //no type
            [
                '
                    SELECT * FROM prepareTableData
                        WHERE s_data = :s_data
                ',
                [
                    's_data' => '3_1',
                ],
                [
                    [
                        'i_data' => 3,
                        'f_data' => 31.1,
                        's_data' => '3_1',
                    ],
                ],
            ],
        ];
    }

    /**
    */
    #[Test]
    #[DataProvider('executeQueryProvider')]
    public function executeQuery(
        string $sql,
        ?array $binds = [],
        ?array $expect = null,
    ) {
        //$this->markTestIncomplete('--- markTestIncomplete ---');

        $this->prepareCreateTable();
        $this->prepareTableData();

        $obj = new DatabaseTestTraitObject();

        $actual_stmt = $obj->executeQuery(
            $sql,
            $binds,
            $this->pdo,
        );

        $actual = $actual_stmt->fetchAll();
        $this->assertSame($expect, $actual);
    }

    public static function createTableProvider()
    {
        return [
            [
                'createTable',
                [
                    'i_data' => 'INTEGER',
                    'f_data' => 'REAL',
                    's_data' => 'TEXT',
                    't_data' => 'TEXT',
                ],
            ],
        ];
    }

    /**
    */
    #[Test]
    #[DataProvider('createTableProvider')]
    public function createTable(
        string $tableName,
        array $definition,
    ) {
        //$this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new DatabaseTestTraitObject();

        //specify PDO
        $actual_pdo = $obj->createTable(
            $tableName,
            $definition,
            $this->pdo,
        );

        $sql = "SELECT * FROM {$tableName}";

        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
        } catch (Exception $e) {
            $this->assertSame(1, 0);
        }
        $this->assertSame(1, 1);

        $obj->createConnection('sqlite::memory:');

        //not specify PDO
        $actual_pdo = $obj->createTable(
            $tableName,
            $definition,
        );

        try {
            $stmt = $actual_pdo->prepare($sql);
            $stmt->execute();
        } catch (Exception $e) {
            $this->assertSame(1, 0);
        }
        $this->assertSame(1, 1);

        $this->assertNotSame($this->pdo, $actual_pdo);
    }

    public static function importDataProvider()
    {
        return [
            [
                [
                    [
                        'i_data' => 1,
                        'f_data' => 11.1,
                        's_data' => '1_1',
                    ],
                    [
                        'i_data' => 2,
                        'f_data' => 21.1,
                        's_data' => '2_1',
                    ],
                    [
                        'i_data' => 3,
                        'f_data' => 31.1,
                        's_data' => '3_1',
                    ],
                ],
            ],
        ];
    }

    /**
    */
    #[Test]
    #[DataProvider('importDataProvider')]
    public function importData(
        array $dataset,
    ) {
        //$this->markTestIncomplete('--- markTestIncomplete ---');

        $this->prepareCreateTable();

        $obj = new DatabaseTestTraitObject();

        $stmt = $obj->importData(
            'prepareTableData',
            $dataset,
            $this->pdo,
        );

        $actual = $stmt->fetchAll();
        $this->assertSame($dataset, $actual);
    }

    /**
    */
    #[Test]
    public function rowCount()
    {
        //$this->markTestIncomplete('--- markTestIncomplete ---');

        $this->prepareCreateTable();
        $this->prepareTableData();

        $obj = new DatabaseTestTraitObject();

        $actual = $obj->rowCount(
            $this->tableName,
            $this->pdo,
        );

        $this->assertSame($this->tableRowCount, $actual);
    }

    /**
    */
    #[Test]
    public function truncateTable()
    {
        //$this->markTestIncomplete('--- markTestIncomplete ---');

        $this->prepareCreateTable();
        $this->prepareTableData();

        $obj = new DatabaseTestTraitObject();

        $pdo = $obj->truncateTable(
            $this->tableName,
            $this->pdo,
        );

        $actual = $obj->rowCount(
            $this->tableName,
            $pdo,
        );

        $this->assertSame(0, $actual);
    }
}
