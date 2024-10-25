<?php

declare(strict_types=1);

namespace test\Concerto\sql\symphony;

use test\Concerto\ConcertoTestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use Concerto\sql\symphony\SymphonyColumnDef;

class SymphonyColumnDefTest extends ConcertoTestCase
{
    public static function main1Provider()
    {
        return [
            [
                [
                    'TABLE_CATALOG' => 'SYMPHONY',
                    'TABLE_SCHEMA' => 'ITC_IS',
                    'TABLE_NAME' => 'EMPLOYEE_VIEW',
                    'COLUMN_NAME' => 'IDTUID',
                    'LOGICAL_NAME' => '統一ﾕｰｻﾞＩＤ',
                    'ORDINAL_POSITION' => '1',
                    'COLUMN_DEFAULT' => '',
                    'IS_NULLABLE' => 'YES',
                    'DATA_TYPE' => 'VARCHAR2(20)',
                    'KEY_POSITION' => '',
                    'DESCRIPTION' => '',
                ],
                [
                    'table_catalog' => 'SYMPHONY',
                    'table_schema' => 'ITC_IS',
                    'table_name' => 'EMPLOYEE_VIEW',
                    'column_name' => 'IDTUID',
                    'logical_name' => '統一ﾕｰｻﾞＩＤ',
                    'ordinal_position' => 1,
                    'column_default' => '',
                    'is_nullable' => true,
                    'data_type' => 'text',
                    'key_position' => '',
                    'description' => '',
                ],
            ],
            [
                [
                    'TABLE_CATALOG' => 'SYMPHONY',
                    'TABLE_SCHEMA' => 'ITC_IS',
                    'TABLE_NAME' => 'HAN_HANYO_VIEW',
                    'COLUMN_NAME' => 'HK_DATE',
                    'LOGICAL_NAME' => '発行日',
                    'ORDINAL_POSITION' => '21',
                    'IS_NULLABLE' => 'NO',
                    'DATA_TYPE' => 'DATE',
                ],
                [
                    'table_catalog' => 'SYMPHONY',
                    'table_schema' => 'ITC_IS',
                    'table_name' => 'HAN_HANYO_VIEW',
                    'column_name' => 'HK_DATE',
                    'logical_name' => '発行日',
                    'ordinal_position' => 21,
                    'is_nullable' => false,
                    'data_type' => 'date',
                ],
            ],
        ];
    }

    #[Test]
    #[DataProvider('main1Provider')]
    public function main1(
        array $columns,
        array $expects,
    ) {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new SymphonyColumnDef($columns);

        foreach ($expects as $name => $expect) {
            $this->assertEquals(
                $expect,
                isset($obj->$name) ? $obj->$name : null,
            );
        }
    }
}
