<?php

declare(strict_types=1);

namespace test\Concerto\sql\symphony;

use test\Concerto\ConcertoTestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use Concerto\sql\symphony\TempTableSqlBuilder;
use Concerto\conf\{
    Config,
    ConfigReaderCsv,
};

class TempTableSqlBuilderTest extends ConcertoTestCase
{
    protected static Config $config;

    public static function setUpBeforeClass(): void
    {
        self::$config = new Config(
            new ConfigReaderCsv(
                implode(
                    DIRECTORY_SEPARATOR,
                    [
                        __DIR__,
                        '..',
                        '..',
                        '..',
                        '..',
                        '..',
                        '_config',
                        'common',
                        'database',
                        'symphony_columns.csv',
                    ],
                ),
            ),
        );
    }

    public static function findTableDefsProvider()
    {
        return [
            [
                'HAN_HANYO_VIEW',
                38,
            ],
        ];
    }

    #[Test]
    #[DataProvider('findTableDefsProvider')]
    public function findTableDefs(
        string $table_name,
        int $expect_count,
    ) {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new TempTableSqlBuilder(
            self::$config,
        );

        $actuals = $this->callPrivateMethod(
            $obj,
            'findTableDefs',
            [$table_name],
        );

        $this->assertEquals(
            $expect_count,
            count($actuals),
        );

        $this->assertTrue(
            isset($actuals[0]->table_name) &&
                $actuals[0]->table_name === $table_name,
        );
    }

    public static function buildTableSqlProvider()
    {
        return [
            [
                'TMAL0190',
                'CREATE TEMP TABLE symphony_tmal0190 (' .
                    'ins_day date,' .
                    'up_day date,' .
                    'rev_task text,' .
                    'rev_tanto text,' .
                    'tanka_no text,' .
                    'sp numeric,' .
                    'net numeric' .
                    ')',
            ],
        ];
    }

    #[Test]
    #[DataProvider('buildTableSqlProvider')]
    public function buildTableSql(
        string $table_name,
        string $expect,
    ) {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new TempTableSqlBuilder(
            self::$config,
        );

        $actual = $obj->buildTableSql($table_name);

        $this->assertEquals(
            $expect,
            $actual,
        );
    }

    public static function buildSelectSqlProvider()
    {
        return [
            [
                'TMAL0190',
                "ins_day > '2024-07-21'",
                'SELECT ' .
                    'INS_DAY,' .
                    'UP_DAY,' .
                    'REV_TASK,' .
                    'REV_TANTO,' .
                    'TANKA_NO,' .
                    'SP,' .
                    'NET' .
                    ' FROM TMAL0190 ' .
                    "WHERE ins_day > '2024-07-21'",
            ],
        ];
    }

    #[Test]
    #[DataProvider('buildSelectSqlProvider')]
    public function buildSelectSql(
        string $table_name,
        string $where,
        string $expect,
    ) {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new TempTableSqlBuilder(
            self::$config,
        );

        $actual = $obj->buildSelectSql(
            $table_name,
            $where,
        );

        $this->assertEquals(
            $expect,
            $actual,
        );
    }

    public static function buildProvider()
    {
        return [
            [
                'TMAL0190',
                "ins_day > '2024-07-21'",
                'CREATE TEMP TABLE symphony_tmal0190 (' .
                    'ins_day date,' .
                    'up_day date,' .
                    'rev_task text,' .
                    'rev_tanto text,' .
                    'tanka_no text,' .
                    'sp numeric,' .
                    'net numeric' .
                    ')',
                'SELECT ' .
                    'INS_DAY,' .
                    'UP_DAY,' .
                    'REV_TASK,' .
                    'REV_TANTO,' .
                    'TANKA_NO,' .
                    'SP,' .
                    'NET' .
                    ' FROM TMAL0190 ' .
                    "WHERE ins_day > '2024-07-21'",
            ],
        ];
    }

    #[Test]
    #[DataProvider('buildProvider')]
    public function build(
        string $table_name,
        string $where,
        string $expect_table,
        string $expect_select,
    ) {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new TempTableSqlBuilder(
            self::$config,
        );

        $actual = $obj->build(
            $table_name,
            $where,
        );

        $this->assertEquals(
            [$expect_table,$expect_select],
            $actual,
        );
    }
}
