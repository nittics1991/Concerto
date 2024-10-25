<?php

declare(strict_types=1);

namespace Concerto\test\sql\symphony;

use test\Concerto\ConcertoTestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use Concerto\sql\symphony\SymphonyLink;
use PDO;
use PDOStatement;

class SymphonyLinkTest extends ConcertoTestCase
{
    protected static PDO $concerto;

    protected static PDO $symphony;

    public static function setUpBeforeClass(): void
    {
        self::$concerto = new PDO(
            $GLOBALS['DB_DSN'],
            $GLOBALS['DB_USER'],
            $GLOBALS['DB_PASSWD'],
        );

        self::$symphony = new PDO(
            $GLOBALS['SYMPHONY_DSN'],
            $GLOBALS['SYMPHONY_USER'],
            $GLOBALS['SYMPHONY_PASSWD'],
        );
    }

    public static function parseTableNameProvider()
    {
        return [
            [
                'CREATE TEMP TABLE aaa (id INT)',
                'aaa',
            ],
            [
                'CREATE TABLE bbb (id INT)',
                'bbb',
            ],
        ];
    }

    #[Test]
    #[DataProvider('parseTableNameProvider')]
    public function parseTableName(
        string $tableSql,
        string $expect,
    ) {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new SymphonyLink(
            self::$symphony,
            self::$concerto,
        );

        $actual = $this->callPrivateMethod(
            $obj,
            'parseTableName',
            [$tableSql],
        );

        $this->assertEquals(
            $expect,
            $actual,
        );
    }

    public static function readTableProvider()
    {
        return [
            [
                "SELECT *
                    FROM ITC_IS.TMAL0030
                    WHERE gb_cd LIKE 'I%'
                ",
                [],
            ],
            [
                "SELECT *
                    FROM ITC_IS.TMAL0030
                    WHERE gb_cd LIKE :gb_cd
                ",
                [
                    'gb_cd' => 'I%'
                ],
            ],
        ];
    }

    #[Test]
    #[DataProvider('readTableProvider')]
    public function readTable(
        string $selectSql,
        array $selectParams,
    ) {
     // $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new SymphonyLink(
            self::$symphony,
            self::$concerto,
        );

        $stmt = $this->callPrivateMethod(
            $obj,
            'readTable',
            [$selectSql, $selectParams],
        );

        $this->assertTrue(
            $stmt instanceof PDOStatement,
        );

        $row = $stmt->fetch();

        $this->assertTrue(
            is_array($row) && !empty($row),
        );
    }

    public static function exportCsvFileProvider()
    {
        return [
            [
                "SELECT *
                    FROM ITC_IS.TMAL0030
                    WHERE gb_cd LIKE 'I%'
                ",
                [],
            ],
        ];
    }

    #[Test]
    #[DataProvider('exportCsvFileProvider')]
    public function exportCsvFile(
        string $selectSql,
        array $selectParams,
    ) {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new SymphonyLink(
            self::$symphony,
            self::$concerto,
        );

        $stmt = $this->callPrivateMethod(
            $obj,
            'readTable',
            [$selectSql, $selectParams],
        );

        $file = $this->callPrivateMethod(
            $obj,
            'exportCsvFile',
            [$stmt],
        );

        $contents = file_get_contents($file);

        $this->assertTrue(
            mb_strlen($contents) > 0,
        );
    }

    public static function createTempTableProvider()
    {
        return [
            [
                //NOT NULL—ñ‚àphp‚É“Ç‚Ýž‚ÆNULL
                "
                    CREATE TEMP TABLE SymphonyLink (
                        code TEXT PRIMARY KEY,
                        name TEXT,
                        has_null_col TEXT,
                        no_null_col TEXT NOT NULL DEFAULT ''
                    )
                ",
                "
                    SELECT gb_cd,
                        sosiki_name,
                        htb_bmn_cd, 
                        htb_bmn_cd AS dmy
                    FROM ITC_IS.TMAL0030
                    WHERE gb_cd LIKE :gb_cd
                ",
                [
                    'gb_cd' => 'I%'
                ],
            ],
        ];
    }

    #[Test]
    #[DataProvider('createTempTableProvider')]
    public function createTempTable(
        string $tableSql,
        string $selectSql,
        array $selectParams,
    ) {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new SymphonyLink(
            self::$symphony,
            self::$concerto,
        );

        $table_name = $obj->createTempTable(
            $tableSql,
            $selectSql,
            $selectParams,
        );

        $sql = "SELECT * FROM {$table_name}";

        $stmt = self::$concerto->prepare($sql);

        $stmt->execute();

        $contents = $stmt->fetchAll();

        $this->assertTrue(
            count($contents) > 0,
        );
    }
}
