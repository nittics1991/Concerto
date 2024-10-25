<?php

declare(strict_types=1);

namespace test\Concerto\test;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use test\Concerto\{
    ConcertoTestCase,
    RecordsetTestTrait,
};
use ArrayObject;
use IteratorAggregate;
use SplFileObject;
use Traversable;

class CsvFileObject implements IteratorAggregate
{
    private SplFileObject $csv;

    private array $headers;

    public function __construct(
        string $path,
    ) {
        $this->csv = new SplFileObject(
            $path,
            'r',
        );

        $this->csv->setFlags(
            SplFileObject::READ_CSV |
            SplFileObject::READ_AHEAD |
            SplFileObject::SKIP_EMPTY,
        );

        $this->csv->setCsvControl(
            ',',
        );

        $this->headers = $this->csv->fgetcsv();
    }

    public function getIterator(): Traversable
    {
        $isFirst = true;

        foreach ($this->csv as $row) {
            if ($isFirst) {
                $isFirst = false;
                continue;
            }

            yield array_combine(
                $this->headers,
                $row,
            );
        }
    }
}

////////////////////////////////////////////////////////////

class RecordsetTestTraitTest extends ConcertoTestCase
{
    use RecordsetTestTrait;

    private static array $records = [
        [
            'id' => 1,
            'prefecture' => 'tokyo',
            'city' => 'fuchu',
        ],
        [
            'id' => 11,
            'prefecture' => 'kanagawa',
            'city' => 'kawasaki',
        ],
        [
            'id' => 3,
            'prefecture' => 'tokyo',
            'city' => 'chiyoda',
        ],
        [
            'id' => 2,
            'prefecture' => 'tokyo',
            'city' => 'shinjyuku',
        ],
    ];

    public function all(
        mixed $records,
        array $expectTable,
        int $expectCount,
        array $expectHeader,
    ) {
        $this->assertEquals(
            $expectTable,
            $this->itaratorsToTable($records),
        );

        $this->assertEquals(
            $expectCount,
            $this->recordsetCount($records),
        );

        $this->assertEquals(
            $expectHeader,
            $this->recordsetHeader($records),
        );
    }

    public static function tableRecordsetProvider()
    {
        return [
            [
                self::$records,
                self::$records,
                count(self::$records),
                array_keys(self::$records[0]),
            ],
        ];
    }

    /**
    */
    #[Test]
    #[DataProvider('tableRecordsetProvider')]
    public function tableRecordset(
        array $records,
        array $expectTable,
        int $expectCount,
        array $expectHeader,
    ) {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->all(
            $records,
            $expectTable,
            $expectCount,
            $expectHeader,
        );
    }

    public static function arrayInObjectRecordsetProvider()
    {
        $records = [];

        foreach (self::$records as $list) {
            $records[] = new ArrayObject($list);
        }

        return [
            [
                $records,
                self::$records,
                count(self::$records),
                array_keys(
                    iterator_to_array(self::$records[0]),
                ),
            ],
        ];
    }

    /**
    */
    #[Test]
    #[DataProvider('arrayInObjectRecordsetProvider')]
    public function arrayInObjectRecordset(
        array $records,
        array $expectTable,
        int $expectCount,
        array $expectHeader,
    ) {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->all(
            $records,
            $expectTable,
            $expectCount,
            $expectHeader,
        );
    }

    public static function objectInObjectRecordsetProvider()
    {
        $records = [];

        foreach (self::$records as $list) {
            $records[] = new ArrayObject($list);
        }

        return [
            [
                new ArrayObject($records),
                self::$records,
                count(self::$records),
                array_keys(
                    iterator_to_array(self::$records[0]),
                ),
            ],
        ];
    }

    /**
    */
    #[Test]
    #[DataProvider('objectInObjectRecordsetProvider')]
    public function objectInObjectRecordset(
        ArrayObject $records,
        array $expectTable,
        int $expectCount,
        array $expectHeader,
    ) {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->all(
            $records,
            $expectTable,
            $expectCount,
            $expectHeader,
        );
    }

    public static function csvFileObjectRecordsetProvider()
    {
        $csv_path = implode(
            DIRECTORY_SEPARATOR,
            [
                __DIR__,
                'data',
                'recordset_test.csv',
            ],
        );

        $csv = new CsvFileObject($csv_path);

        return [
            [
                $csv,
                self::$records,
                count(self::$records),
                array_keys(self::$records[0]),
            ],
        ];
    }

    /**
    */
    #[Test]
    #[DataProvider('csvFileObjectRecordsetProvider')]
    public function splFileObjectRecordset(
        CsvFileObject $records,
        array $expectTable,
        int $expectCount,
        array $expectHeader,
    ) {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->all(
            $records,
            $expectTable,
            $expectCount,
            $expectHeader,
        );
    }
}
