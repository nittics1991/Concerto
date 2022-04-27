<?php

declare(strict_types=1);

namespace test\Concerto\sql\simpleTable;

use test\Concerto\ConcertoTestCase;
use Concerto\sql\simpleTable\TableDefinition;
use Concerto\standard\ModelData;
use Concerto\standard\ModelDb;

class StubTableDefinition extends TableDefinition
{
    protected array $columnMap = [
        'string' => 'STRING',
        'boolean' => 'BOOLEAN',
        'double' => 'DOUBLE',
        'integer' => 'INTEGER',
        'float' => 'FLOAT',
        'datetime' => 'DATETIME',
    ];
}

/////////////////////////////////////////////////////////////////////

class TableDefinitionTest extends ConcertoTestCase
{
    public $object;

    public function setUp(): void
    {
    }

    /**
    *   @test
    */
    public function createTable()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $table = $this->createMock(ModelDb::class);
        $table->method('getSchema')
            ->willReturn('mytable')
        ;

        $columns = $this-> getMockBuilder(ModelData::class)
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

        $this->object = new StubTableDefinition($table, $columns);

        $this->assertEquals(
            $schema,
            $this->getPrivateProperty($columns, 'schema')
        );

        $expect = "CREATE TABLE mytable ('prop_s' STRING, 'prop_b' BOOLEAN, 'prop_d' DOUBLE, 'prop_i' INTEGER, 'prop_f' DOUBLE, 'prop_t' DATETIME)";

        $this->assertEquals(
            $expect,
            $this->object->createTable()
        );
    }
}
