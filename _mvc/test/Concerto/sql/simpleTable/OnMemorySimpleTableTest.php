<?php

declare(strict_types=1);

namespace test\Concerto\sql\simpleTable;

use test\Concerto\ConcertoTestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use PDO;
use RuntimeException;
use Concerto\standard\{
    ModelData,
    ModelDb,
};
use Concerto\sql\simpleTable\OnMemorySimpleTable;

class SimpleTableStab1 extends ModelDb
{
    protected string $schema = 'test.SimpleTableStab1';
}

class SimpleTableStab1Data extends ModelData
{
    protected static array $schema = [
        'b_prop' => ModelData::BOOLEAN,
        'i_prop' => ModelData::INTEGER,
        'f_prop' => ModelData::FLOAT,
        'd_prop' => ModelData::DOUBLE,
        's_prop' => ModelData::STRING,
        't_prop' => ModelData::DATETIME,
    ];
}

class OnMemorySimpleTableTest extends ConcertoTestCase
{
    /**
    */
    #[Test]
    public function getTableName()
    {
        // $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new OnMemorySimpleTable();
        $pdo = $obj->getPDO();
        $modelDb = new SimpleTableStab1($pdo);

        $this->assertEquals(
            'test_SimpleTableStab1',
            $obj->getTableName($modelDb),
        );
    }

    public static function setTypeConvertMapProvider()
    {
        return [
            [
                'boolean',
                'VARCHAR(1)'
            ],
            [
                'integer',
                'smallint'
            ],
        ];
    }

    /**
    */
    #[Test]
    #[DataProvider('setTypeConvertMapProvider')]
    public function setTypeConvertMap($model_type, $db_type)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new OnMemorySimpleTable();
        $obj->setTypeConvertMap($model_type, $db_type);

        $definisions = $this->getPrivateProperty($obj, 'type_convert_map');

        $this->assertEquals($db_type, $definisions[$model_type]);
    }

    /**
    */
    #[Test]
    public function createAndTruncateTable()
    {
        // $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new OnMemorySimpleTable();
        $pdo = $obj->getPDO();
        $modelDb = new SimpleTableStab1($pdo);

        //create table
        $obj->createFromModelDb($modelDb);

        $created_columns = $obj->columns($modelDb);

        array_multisort(
            array_column($created_columns, 'name'),
            SORT_ASC,
            SORT_REGULAR,
            $created_columns,
        );

        $defined_columns = $modelDb->createModel()
            ->getInfo();

        ksort($defined_columns);

        $this->assertEquals(
            array_keys($defined_columns),
            array_column($created_columns, 'name'),
        );

        //truncate table
        $obj->truncate($modelDb);

        $table_name = $obj->getTableName($modelDb);
        $sql = "SELECT COUNT(*) FROM {$table_name}";

        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        $this->assertEquals(
            0,
            (int)$stmt->fetchColumn()
        );
    }
}
