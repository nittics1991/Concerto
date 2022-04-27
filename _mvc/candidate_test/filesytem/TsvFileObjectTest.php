<?php

declare(strict_types=1);

namespace test\Concerto\filesystem;

use test\Concerto\ConcertoTestCase;
use candidate\filesystem\TsvFileObject;
use DateTimeInterface;
use PDO;
use candidate\sql\simpleTable\OnMemorySimpleTable;
use candidate\standard\{
    ModelData,
    ModelDb,
};

class TsvFileObjectDummy1 extends ModelDb
{
    protected $schema = 'test.TsvFileObjectDummy1';
}

class TsvFileObjectDummy1Data extends ModelData
{
    protected static $schema = [
        'b_prop' => ModelData::BOOLEAN,
        'i_prop' => ModelData::INTEGER,
        'f_prop' => ModelData::FLOAT,
        'd_prop' => ModelData::DOUBLE,
        's_prop' => ModelData::STRING,
        't_prop' => ModelData::DATETIME,
    ];
}

///////////////////////////////////////////////////////////////////////

class TsvFileObjectTest extends ConcertoTestCase
{
    private static $simpleTable;
    private static $pdo;
    private static $modelDb;

    public static function setUpBeforeClass(): void
    {
        self::$simpleTable = new OnMemorySimpleTable();
        self::$pdo = self::$simpleTable->getPDO();
        self::$modelDb = new TsvFileObjectDummy1(self::$pdo);
        self::$simpleTable->createFromModelDb(self::$modelDb);
    }

    /**
    *   @test
    */
    public function readTsv()
    {
     // $this->markTestIncomplete();

        $utf8_tsv = new TsvFileObject(
            realpath(
                __DIR__ . '/data/utf8_lf_data.tsv'
            ),
            'r',
        );

        $utf8_array = $utf8_tsv->readTsv(self::$modelDb);

        foreach ($utf8_array as $no => $modelData) {
            if (
                is_object($modelData) &&
                $modelData instanceof TsvFileObjectDummy1Data
            ) {
                $this->assertEquals(1, 1);
            } else {
                $this->assertEquals(1, 0, "failue utf8_tsv no={$no}");
            }
        }

        //デバッグ用
        // var_dump($utf8_array);echo "\n";

        //EXCEL形式
        $sjis_tsv = new TsvFileObject(
            realpath(
                __DIR__ . '/data/sjis_cr_lf_data.tsv'
            ),
            'r',
        );

        $sjis_array = $sjis_tsv->readTsv(self::$modelDb, 'SJIS');

        foreach ($sjis_array as $no => $modelData) {
            if (
                is_object($modelData) &&
                $modelData instanceof TsvFileObjectDummy1Data
            ) {
                $this->assertEquals(1, 1);
            } else {
                $this->assertEquals(1, 0, "failue sjis_tsv no={$no}");
            }
        }

        //デバッグ用
        // var_dump($sjis_array);echo "\n";

        //両者の比較
        if (count($utf8_array) === count($sjis_array)) {
                $this->assertEquals(1, 1);
        } else {
                $this->assertEquals(1, 0, "unmatch count");
        }

        array_map(
            function ($modelData1, $modelData2) {
                $no = 0;
                $dataset1 = $modelData1->toArray();
                $dataset2 = $modelData2->toArray();

                array_map(
                    function ($val1, $val2) use (&$no) {
                        if ($val1 === $val2) {
                            $this->assertEquals(1, 1);
                        } elseif (is_object($val1) && is_object($val2)) {
                            $this->assertEquals(
                                $val1->format(DateTimeInterface::ATOM),
                                $val2->format(DateTimeInterface::ATOM),
                                "unmatch data no={$no}"
                            );
                        } else {
                            $this->assertEquals(
                                1,
                                0,
                                "unmatch data no={$no}"
                            );
                        }
                        $no++;
                    },
                    $dataset1,
                    $dataset2,
                );
            },
            $utf8_array,
            $sjis_array,
        );
    }
}
