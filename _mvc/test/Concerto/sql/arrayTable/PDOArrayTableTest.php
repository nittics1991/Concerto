<?php

declare(strict_types=1);

namespace test\Concerto\sql\arrayTable;

use ArrayObject;
use BadMethodCallException;
use DateTime;
use DateTimeImmutable;
use Exception;
use PDO;
use test\Concerto\ConcertoTestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use Concerto\sql\arrayTable\PDOArrayTable;

class PDOArrayTableTest extends ConcertoTestCase
{
    public $object;
    public $pdo;

    public function setUp(): void
    {
        $this->pdo = new PDO("sqlite::memory:");
        ini_set('date.timezone', 'Asia/Tokyo');
    }

    /**
    *   @return array [method_name, [argument1,...], expect]
    */

    public static function callMagicMethodProvider()
    {
        return [
            ['getAttribute', [PDO::ATTR_ERRMODE], PDO::ERRMODE_EXCEPTION],
            ['getAttribute', [PDO::ATTR_ORACLE_NULLS], PDO::NULL_NATURAL],
        ];
    }

    /**
    */
    #[Test]
    #[DataProvider('callMagicMethodProvider')]
    public function callMagicMethod($method, $args, $expect)
    {
        // $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new PDOArrayTable($this->pdo);
        $actual = call_user_func_array(
            [$this->pdo, $method],
            $args
        );

        $this->assertEquals($expect, $actual);
    }

    /**
    *   @return array [data]
    */
    public static function mainData()
    {
        return [
            //empty data
            [
                []
            ],
            //null list
            [
                [
                    'prop_b' => null,
                     'prop_i' => null,
                     'prop_f' => null,
                     'prop_s' => null,
                     'prop_d' => null,
                     'prop_d2' => null,
                ],
            ],
            //empty & null list & simple ok data & go through
            [
                [],
                [
                    'prop_b' => null,
                     'prop_i' => null,
                     'prop_f' => null,
                     'prop_s' => null,
                     'prop_d' => null,
                     'prop_d2' => null,
                ],
                [
                    'prop_b' => true,
                     'prop_i' => 1,
                     'prop_f' => 11.1,
                     'prop_s' => 'AAA',
                     'prop_d' => '2012-1-1 0:00:0',
                     'prop_d2' => new DateTime('2012-1-1 0:0:0+09:00'),
                ],
                [
                    'prop_b' => false,
                     'prop_i' => 11,
                     'prop_f' => 12.1,
                     'prop_s' => 'BBB',
                     'prop_d' => '2013-12-31 23:59:59',
                     'prop_d2' => new DateTimeImmutable('2012-1-1 0:0:0+09:00'),
                ],
                [
                    'prop_b' => true,
                     'prop_i' => -3,
                     'prop_f' => -3.2,
                     'prop_s' => 'CCC',
                     'prop_d' => '2012-04-03 12:34:56',
                     'prop_d2' => new DateTime('2012-1-1 0:0:0+09:00'),
                ],
                [
                    'prop_b' => false,
                     'prop_i' => 0,
                     'prop_f' => 0.0,
                     'prop_s' => 'DDD',
                     'prop_d' => '2011-2-29 0:0:0',
                     'prop_d2' => new DateTimeImmutable('2012-1-1 0:0:0+09:00'),
                ],
                [
                    'prop_b' => true,
                     'prop_i' => 1,
                     'prop_f' => 11.1,
                     'prop_s' => 'AAA',
                     'prop_d' => '2012-12-31 25:00:0',
                     'prop_d2' => new DateTime('2012-1-1 0:0:0+09:00'),
                ],
            ],
            //array & object data
            [
                [
                    'prob_a' => ['DUMMY1' => 1, 2],
                     'prop_o' => new ArrayObject(['DUMMY1' => 1, 2]),
                ],
            ],
        ];
    }

    /**
    *   @return array [data, expect]
    */
    public static function createTableSchemaProvider()
    {
        return array_map(
            function ($data, $expect) {
                return [$data, $expect];
            },
            static::mainData(),
            [
                [],
                [],
                [
                    'prop_b' => 'text',
                    'prop_i' => 'integer',
                    'prop_f' => 'numeric',
                    'prop_s' => 'text',
                    'prop_d' => 'timestamp',
                    'prop_d2' => 'timestamp',
                ],
                [],
            ],
        );
    }

    /**
    */
    #[Test]
    #[DataProvider('createTableSchemaProvider')]
    public function createTableSchema($data, $expect)
    {
        // $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new PDOArrayTable($this->pdo);
        $actual = $this->callPrivateMethod(
            $obj,
            'createTableSchema',
            [$data]
        );

        $this->assertEquals($expect, $actual);
    }

    /**
    *   @return array [table_name, schema, $expect]
    */
    public static function buildCreateTableSqlProvider()
    {
        return [
            //empty
            [
                'empty_schema',
                [],
                [
                    'CREATE',
                    'TABLE',
                    'empty_schema',
                    '(',
                    ')',
                ],
            ],
            //all type
            [
                'success1',
                [
                    'prop_b' => PDOArrayTable::TYPE_TEXT,
                    'prop_i' => PDOArrayTable::TYPE_INTEGER,
                    'prop_f' => PDOArrayTable::TYPE_NUMERIC,
                    'prop_s' => PDOArrayTable::TYPE_TEXT,
                    'prop_d' => PDOArrayTable::TYPE_TIMESTAMP,
                    'prop_d2' => PDOArrayTable::TYPE_TIMESTAMP,
                ],
                [
                    'CREATE',
                    'TABLE',
                    'success1',
                    '(',
                    'prop_b',
                    'text',
                    'prop_i' ,
                    'integer',
                    'prop_f',
                    'numeric',
                    'prop_s',
                    'text',
                    'prop_d',
                    'timestamp',
                    'prop_d2',
                    'timestamp',
                    ')',
                ],
            ],
        ];
    }

    /**
    *   parseSqlString
    *
    *   @param string $sql
    *   @return array
    */
    public function parseSqlString(string $sql): array
    {
        $splited = explode(' ', $sql);

        $splited2 = array_map(
            fn($sql) => explode(',', $sql),
            $splited
        );

        $splited3 = [];
        array_map(
            function ($sql) use (&$splited3) {
                    $splited3 = array_merge($splited3, $sql);
            },
            $splited2
        );

        return array_values(
            array_filter(
                $splited3,
                fn($val) => !empty(trim($val)),
            )
        );
    }

    /**
    */
    #[Test]
    #[DataProvider('buildCreateTableSqlProvider')]
    public function buildCreateTableSql($name, $schema, $expect)
    {
        // $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new PDOArrayTable($this->pdo);
        $sql = $this->callPrivateMethod(
            $obj,
            'buildCreateTableSql',
            [$name, $schema]
        );

        $actual = $this->parseSqlString($sql);

        $this->assertEquals($expect, $actual);
    }

    /**
    *   @return array [table_name, schema, $data, expect[sql, binds]]
    */
    public static function buildInsertStatementProvider()
    {
        return [
            [
                'DUMMY11',
                [],
                [],
                [
                    [
                        'INSERT',
                        'INTO',
                        'DUMMY11',
                        '(',
                        ')',
                        'VALUES',
                        '(',
                        ')',
                    ],
                    [],
                ],
            ],
            [
                'DUMMY12',
                [
                    'prop_b' => 'text',
                    'prop_i' => 'integer',
                    'prop_f' => 'numeric',
                    'prop_s' => 'text',
                    'prop_d' => 'timestamp',
                    'prop_d2' => 'timestamp',
                ],
                [
                    [
                        'prop_b' => true,
                         'prop_i' => 1,
                         'prop_f' => 11.1,
                         'prop_s' => 'AAA',
                         'prop_d' => '2012-1-1 0:00:0',
                         'prop_d2' => new DateTime('2012-1-1 0:0:0+09:00'),
                    ],
                    [
                        'prop_b' => false,
                         'prop_i' => 11,
                         'prop_f' => 12.1,
                         'prop_s' => 'BBB',
                         'prop_d' => '2013-12-31 23:59:59',
                         'prop_d2' => new DateTimeImmutable('2012-1-1 0:0:0+09:00'),
                     ],
                ],
                [
                    [
                        'INSERT',
                        'INTO',
                        'DUMMY12',
                        '(',
                        'prop_b',
                        'prop_i',
                        'prop_f',
                        'prop_s',
                        'prop_d',
                        'prop_d2',
                        ')',
                        'VALUES',
                        '(',
                        ':0_0',
                        ':0_1',
                        ':0_2',
                        ':0_3',
                        ':0_4',
                        ':0_5',
                        ')',
                        '(',
                        ':1_0',
                        ':1_1',
                        ':1_2',
                        ':1_3',
                        ':1_4',
                        ':1_5',
                        ')',
                    ],
                    [
                        ':0_0' => '1',
                         ':0_1' => 1,
                         ':0_f2' => 11.1,
                         ':0_3' => 'AAA',
                         ':0_4' => '2012-01-01T00:00:00+09:00',
                         ':0_5' => '2012-01-01T00:00:00+09:00',
                        ':1_0' => '0',
                         ':1_1' => 11,
                         ':1_2' => 12.1,
                         ':1_3' => 'BBB',
                         ':1_4' => '2013-12-31T23:59:59+09:00',
                         ':1_5' => '2012-01-01T00:00:00+09:00',
                     ],
                ],
            ],
        ];
    }

    /**
    */
    #[Test]
    #[DataProvider('buildInsertStatementProvider')]
    public function buildInsertStatement($name, $schema, $data, $expect)
    {
        // $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new PDOArrayTable($this->pdo);
        $dataset = $this->callPrivateMethod(
            $obj,
            'buildInsertStatement',
            [$name, $schema, $data]
        );

        $actual_sql = $this->parseSqlString($dataset[0]);

        $this->assertEquals($expect[0], $actual_sql);
    }

    /**
    *   @return array [table_name, binds, expect]
    */
    public static function doInsertDataProvider()
    {
        return [
            [
                'DUMMY21',
                [],
                [],
            ],
            [
                'DUMMY22',
                [
                    [
                        ':0_0' => '1',
                         ':0_1' => 1,
                         ':0_2' => 11.1,
                         ':0_3' => 'AAA',
                         ':0_4' => '2012-01-01T00:00:00+09:00',
                         ':0_5' => '2012-01-01T00:00:00+09:00',
                     ],
                     [
                        ':1_0' => '0',
                         ':1_1' => 11,
                         ':1_2' => 12.1,
                         ':1_3' => 'BBB',
                         ':1_4' => '2013-12-31T23:59:59+09:00',
                         ':1_5' => '2012-01-01T00:00:00+09:00',
                     ],
                ],
                [
                    [
                        'prop_b' => '1',
                         'prop_i' => 1,
                         'prop_f' => 11.1,
                         'prop_s' => 'AAA',
                         'prop_d' => '2012-01-01T00:00:00+09:00',
                         'prop_d2' => '2012-01-01T00:00:00+09:00',
                    ],
                    [
                        'prop_b' => '0',
                         'prop_i' => 11,
                         'prop_f' => 12.1,
                         'prop_s' => 'BBB',
                         'prop_d' => '2013-12-31T23:59:59+09:00',
                         'prop_d2' => '2012-01-01T00:00:00+09:00',
                     ],
                ],
            ],
        ];
    }

    /**
    */
    #[Test]
    #[DataProvider('doInsertDataProvider')]
    public function doInsertData($name, $binds, $expect)
    {
        // $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new PDOArrayTable($this->pdo);

        $sql = "
            CREATE TABLE {$name} (
                prop_b text,
                prop_i integer,
                prop_f numeric,
                prop_s text,
                prop_d timestamp,
                prop_d2 timestamp
            )
        ";
        $stmt = $obj->prepare($sql);
        $stmt->execute();

        $schema = [
            'prop_b' => 'text',
            'prop_i' => 'integer',
            'prop_f' => 'numeric',
            'prop_s' => 'text',
            'prop_d' => 'timestamp',
            'prop_d2' => 'timestamp',
        ];

        $sql = "
            INSERT INTO {$name} (
        ";

        $sql .= implode(
            ',',
            array_keys($schema)
        );

        $sql .= "
            ) VALUES (
        ";

        $values = [];
        foreach ($binds as $list) {
            $values[]  = implode(',', array_keys($list));
        }

        $sql .= implode('),(', $values) . ")";

        $flatten = array_reduce(
            $binds,
            fn($carry, $list) => array_merge($carry, $list),
            []
        );

        $this->callPrivateMethod(
            $obj,
            'doInsertData',
            [$schema, $sql, $flatten]
        );

        $sql = "
            SELECT *
            FROM {$name}
        ";
        $stmt = $obj->prepare($sql);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $actual = (array)$stmt->fetchAll();

        $this->assertEquals($expect, $actual);
    }

    /**
    *   @return array [table_name, data, expect]
    */
    public static function createTableFromArrayTableSchemaProvider()
    {
        return [
            [
                'DUMMY31',
                [],
                [],
            ],
            [
                'DUMMY32',
                [
                    [
                        'prop_b' => true,
                         'prop_i' => 1,
                         'prop_f' => 11.1,
                         'prop_s' => 'AAA',
                         'prop_d' => '2012-1-1 0:00:0',
                         'prop_d2' => new DateTime('2012-1-1 0:0:0+09:00'),
                    ],
                    [
                        'prop_b' => false,
                         'prop_i' => 11,
                         'prop_f' => 12.1,
                         'prop_s' => 'BBB',
                         'prop_d' => '2013-12-31 23:59:59',
                         'prop_d2' => new DateTimeImmutable('2012-1-1 0:0:0+09:00'),
                    ],
                ],
                [
                    'prop_b' => 'text',
                    'prop_i' => 'integer',
                    'prop_f' => 'numeric',
                    'prop_s' => 'text',
                    'prop_d' => 'timestamp',
                    'prop_d2' => 'timestamp',
                ],
            ],
        ];
    }

    /**
    */
    #[Test]
    #[DataProvider('createTableFromArrayTableSchemaProvider')]
    public function createTableFromArrayTableSchema($name, $data, $expect)
    {
        // $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new PDOArrayTable($this->pdo);

        $actual = $obj->createTableFromArrayTable(
            $name,
            $data
        );

        $this->assertEquals($expect, $actual);
    }

    /**
    *   @return array [table_name, data, expect]
    */
    public static function createTableFromArrayTableProvider()
    {
        return [
            [
                'DUMMY41',
                [],
                null,
            ],
            [
                'DUMMY42',
                [
                    [
                        'prop_b' => true,
                         'prop_i' => 1,
                         'prop_f' => 11.1,
                         'prop_s' => 'AAA',
                         'prop_d' => '2012-1-1 0:00:0',
                         'prop_d2' => new DateTime('2012-1-1 0:0:0+09:00'),
                    ],
                    [
                        'prop_b' => false,
                         'prop_i' => 11,
                         'prop_f' => 12.1,
                         'prop_s' => 'BBB',
                         'prop_d' => '2013-12-31 23:59:59',
                         'prop_d2' => new DateTimeImmutable('2012-1-1 0:0:0+09:00'),
                    ],
                ],
                //sqliteではstringで取得される
                [
                    [
                        'prop_b' => '1',
                         'prop_i' => '1',
                         'prop_f' => '11.1',
                         'prop_s' => 'AAA',
                         'prop_d' => '2012-01-01T00:00:00+09:00',
                         'prop_d2' => '2012-01-01T00:00:00+09:00',
                    ],
                    [
                        'prop_b' => '0',
                         'prop_i' => '11',
                         'prop_f' => '12.1',
                         'prop_s' => 'BBB',
                         'prop_d' => '2013-12-31T23:59:59+09:00',
                         'prop_d2' => '2012-01-01T00:00:00+09:00',
                     ],
                ],
            ],
        ];
    }

    /**
    */
    #[Test]
    #[DataProvider('createTableFromArrayTableProvider')]
    public function createTableFromArrayTable($name, $data, $expect)
    {
        // $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new PDOArrayTable($this->pdo);

        $obj->createTableFromArrayTable(
            $name,
            $data
        );

        $sql = "
            SELECT *
            FROM {$name}
        ";

        try {
            $stmt = $obj->prepare($sql);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $actual = (array)$stmt->fetchAll();
        } catch (Exception $e) {
            if ($expect === null && $e->getCode() === 'HY000') {
                $this->assertEquals(1, 1);
            } else {
                $this->assertEquals(1, 0);
            }
            return;
        }

        $this->assertEquals($expect, $actual);
    }

    /**
    *   @return array [table_name, expect]
    */
    public static function userSetSchemaProvider()
    {
        return [
            [
                'DUMMY51',
                [],
            ],
            [
                'DUMMY52',
                [
                    'prop_b' => PDOArrayTable::TYPE_TEXT,
                    'prop_i' => PDOArrayTable::TYPE_INTEGER,
                    'prop_f' => PDOArrayTable::TYPE_NUMERIC,
                    'prop_s' => PDOArrayTable::TYPE_TEXT,
                    'prop_d' => PDOArrayTable::TYPE_TIMESTAMP,
                    'prop_d2' => PDOArrayTable::TYPE_TIMESTAMP,
                ],
            ],
        ];
    }

    /**
    */
    #[Test]
    #[DataProvider('userSetSchemaProvider')]
    public function userSetSchema($name, $expect)
    {
        // $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new PDOArrayTable($this->pdo);

        $actual = $obj->createTableFromArrayTable(
            $name,
            [],
            $expect
        );

        $this->assertEquals($expect, $actual);
    }

    /**
    *   @return array [table_name, schema, data, expect]
    */
    public static function userSetSchemaUnMatchDataColumnProvider()
    {
        return [
            [
                'DUMMY61',
                [],
                [],
                null,
            ],
            //not defined column data
            [
                'DUMMY62',
                [
                    'prop_b' => PDOArrayTable::TYPE_TEXT,
                    'prop_i' => PDOArrayTable::TYPE_INTEGER,
                    'prop_f' => PDOArrayTable::TYPE_NUMERIC,
                    'prop_s' => PDOArrayTable::TYPE_TEXT,
                    'prop_d' => PDOArrayTable::TYPE_TIMESTAMP,
                    'prop_d2' => PDOArrayTable::TYPE_TIMESTAMP,
                ],
                [
                    [
                         'DUMMY1' => 'NOT DEFINED COLUMN',  //not defined column data
                        'prop_b' => true,
                         'prop_i' => 1,
                         'prop_f' => 11.1,
                         'prop_s' => 'AAA',
                         'prop_d' => '2012-1-1 0:00:0',
                         'prop_d2' => new DateTime('2012-1-1 0:0:0+09:00'),
                    ],
                    [
                        'prop_b' => false,
                         'prop_i' => 11,
                         'prop_f' => 12.1,
                         'prop_s' => 'BBB',
                         'prop_d' => '2013-12-31 23:59:59',
                         'prop_d2' => new DateTimeImmutable('2012-1-1 0:0:0+09:00'),
                    ],
                ],
                [
                    [
                        'prop_b' => '1',
                         'prop_i' => '1',
                         'prop_f' => '11.1',
                         'prop_s' => 'AAA',
                         'prop_d' => '2012-01-01T00:00:00+09:00',
                         'prop_d2' => '2012-01-01T00:00:00+09:00',
                    ],
                    [
                        'prop_b' => '0',
                         'prop_i' => '11',
                         'prop_f' => '12.1',
                         'prop_s' => 'BBB',
                         'prop_d' => '2013-12-31T23:59:59+09:00',
                         'prop_d2' => '2012-01-01T00:00:00+09:00',
                     ],
                ],
            ],
            //few column data
            [
                'DUMMY63',
                [
                    'prop_b' => PDOArrayTable::TYPE_TEXT,
                    'prop_i' => PDOArrayTable::TYPE_INTEGER,
                    'prop_f' => PDOArrayTable::TYPE_NUMERIC,
                    'prop_s' => PDOArrayTable::TYPE_TEXT,
                    'prop_d' => PDOArrayTable::TYPE_TIMESTAMP,
                    'prop_d2' => PDOArrayTable::TYPE_TIMESTAMP,
                ],
                [
                    [
                        'prop_b' => true,
                         // 'prop_i' => 1,      //few data
                         'prop_f' => 11.1,
                         'prop_s' => 'AAA',
                         'prop_d' => '2012-1-1 0:00:0',
                         'prop_d2' => new DateTime('2012-1-1 0:0:0+09:00'),
                    ],
                    [
                        'prop_b' => false,
                         'prop_i' => 11,
                         'prop_f' => 12.1,
                         'prop_s' => 'BBB',
                         'prop_d' => '2013-12-31 23:59:59',
                         'prop_d2' => new DateTimeImmutable('2012-1-1 0:0:0+09:00'),
                    ],
                ],
                [
                    [
                        'prop_b' => '1',
                         'prop_i' => null,    //few data expect
                         'prop_f' => '11.1',
                         'prop_s' => 'AAA',
                         'prop_d' => '2012-01-01T00:00:00+09:00',
                         'prop_d2' => '2012-01-01T00:00:00+09:00',
                    ],
                    [
                        'prop_b' => '0',
                         'prop_i' => '11',
                         'prop_f' => '12.1',
                         'prop_s' => 'BBB',
                         'prop_d' => '2013-12-31T23:59:59+09:00',
                         'prop_d2' => '2012-01-01T00:00:00+09:00',
                     ],
                ],
            ],
        ];
    }

    /**
    */
    #[Test]
    #[DataProvider('userSetSchemaUnMatchDataColumnProvider')]
    public function userSetSchemaUnMatchDataColumn($name, $schema, $data, $expect)
    {
        // $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new PDOArrayTable($this->pdo);

        $obj->createTableFromArrayTable(
            $name,
            $data,
            $schema
        );

        $sql = "
            SELECT *
            FROM {$name}
        ";

        try {
            $stmt = $obj->prepare($sql);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $actual = (array)$stmt->fetchAll();
        } catch (Exception $e) {
            if ($expect === null && $e->getCode() === 'HY000') {
                $this->assertEquals(1, 1);
            } else {
                $this->assertEquals(1, 0, print_r($e, true));
            }
            return;
        }

        $this->assertEquals($expect, $actual);
    }


    /**
    *   @return array [table_name, schema, data, expect]
    */
    public static function unMatchDataColumnProvider()
    {
        return [
            [
                'DUMMY61',
                [],
                null,
            ],
            //not defined column data
            [
                'DUMMY62',
                [
                    [
                         'DUMMY1' => 'NOT DEFINED COLUMN',  //not defined column data
                        'prop_b' => true,
                         'prop_i' => 1,
                         'prop_f' => 11.1,
                         'prop_s' => 'AAA',
                         'prop_d' => '2012-1-1 0:00:0',
                         'prop_d2' => new DateTime('2012-1-1 0:0:0+09:00'),
                    ],
                    [
                        'prop_b' => false,
                         'prop_i' => 11,
                         'prop_f' => 12.1,
                         'prop_s' => 'BBB',
                         'prop_d' => '2013-12-31 23:59:59',
                         'prop_d2' => new DateTimeImmutable('2012-1-1 0:0:0+09:00'),
                    ],
                ],
                [
                    [
                        'prop_b' => '1',
                         'prop_i' => '1',
                         'prop_f' => '11.1',
                         'prop_s' => 'AAA',
                         'prop_d' => '2012-01-01T00:00:00+09:00',
                         'prop_d2' => '2012-01-01T00:00:00+09:00',
                         'DUMMY1' => 'NOT DEFINED COLUMN',  //not defined column data
                    ],
                    [
                        'prop_b' => '0',
                         'prop_i' => '11',
                         'prop_f' => '12.1',
                         'prop_s' => 'BBB',
                         'prop_d' => '2013-12-31T23:59:59+09:00',
                         'prop_d2' => '2012-01-01T00:00:00+09:00',
                         'DUMMY1' => null,  //not defined column data
                     ],
                ],
            ],
            //few column data
            [
                'DUMMY63',
                [
                    [
                        'prop_b' => true,
                         // 'prop_i' => 1,      //few data
                         'prop_f' => 11.1,
                         'prop_s' => 'AAA',
                         'prop_d' => '2012-1-1 0:00:0',
                         'prop_d2' => new DateTime('2012-1-1 0:0:0+09:00'),
                    ],
                    [
                        'prop_b' => false,
                         'prop_i' => 11,
                         'prop_f' => 12.1,
                         'prop_s' => 'BBB',
                         'prop_d' => '2013-12-31 23:59:59',
                         'prop_d2' => new DateTimeImmutable('2012-1-1 0:0:0+09:00'),
                    ],
                ],
                [
                    [
                        'prop_b' => '1',
                         'prop_i' => null,    //few data expect
                         'prop_f' => '11.1',
                         'prop_s' => 'AAA',
                         'prop_d' => '2012-01-01T00:00:00+09:00',
                         'prop_d2' => '2012-01-01T00:00:00+09:00',
                    ],
                    [
                        'prop_b' => '0',
                         'prop_i' => '11',
                         'prop_f' => '12.1',
                         'prop_s' => 'BBB',
                         'prop_d' => '2013-12-31T23:59:59+09:00',
                         'prop_d2' => '2012-01-01T00:00:00+09:00',
                     ],
                ],
            ],
        ];
    }

    /**
    */
    #[Test]
    #[DataProvider('unMatchDataColumnProvider')]
    public function unMatchDataColumn($name, $data, $expect)
    {
        // $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new PDOArrayTable($this->pdo);

        $obj->createTableFromArrayTable(
            $name,
            $data,
        );

        $sql = "
            SELECT *
            FROM {$name}
        ";

        try {
            $stmt = $obj->prepare($sql);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $actual = (array)$stmt->fetchAll();
        } catch (Exception $e) {
            if ($expect === null && $e->getCode() === 'HY000') {
                $this->assertEquals(1, 1);
            } else {
                $this->assertEquals(1, 0, print_r($e, true));
            }
            return;
        }

        $this->assertEquals($expect, $actual);
    }
}
