<?php

declare(strict_types=1);

namespace test\Concerto\database;

use test\Concerto\AbstractSqliteTestCase;
use Concerto\database\CyokkaKeikaku;
use Concerto\database\CyokkaKeikakuData;
use PDO;
use League\Csv\Reader;

class CyokkaKeikakuTest extends AbstractSqliteTestCase
{
    private $modelDb;
    private $modelData;
    private $tablename = 'public_cyokka_keikaku';
    private $file;

    protected function setUp(): void
    {
        $this->initPdo();
        $this->modelDb = new CyokkaKeikaku($this->pdo);
        $this->modelData = new CyokkaKeikakuData();

        //to correspond Sqlite
        $this->setPrivateProperty($this->modelDb, 'schema', $this->tablename);
        $this->setPrivateProperty($this->modelDb, 'name', $this->tablename);

        $this->setupTable($this->modelDb, $this->modelData);

        $this->file =
            __DIR__ . DIRECTORY_SEPARATOR .
            implode(
                DIRECTORY_SEPARATOR,
                ['data', 'cyokka_keikaku.csv'],
            );

        $dataset = $this->changeDatasetToArray();

        $this->importData(
            $this->tablename,
            $dataset,
            $this->pdo,
        );
    }

    protected function getDataSet()
    {
        $dataSet = Reader::createFromPath($this->file);
        return $dataSet;
    }

    /**
    * データセットからデータを配列で返す
    *
    */
    public function changeDatasetToArray()
    {
        $dataset = $this->getDataSet();
        $schema = $this->modelData->getInfo();

        $records = $dataset->getRecords(
            array_keys($schema),
        );

        $result = [];
        foreach ($records as $record) {
            $result[] = $record;
        }

        return $result;
    }

    public function testRowCount()
    {
        $dataset = $this->getDataSet();
        $table = $this->fetchAllData($this->modelDb);

        $this->assertEquals(
            count($dataset),
            count($table),
        );
    }

    /**
    * ModelData配列を配列に変換する
    *
    */
    public function changeDatabaseToArray($objects)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $actual = [];
        foreach ($objects as $obj) {
            $array = $obj->toArray();

            $dumy = [];
            foreach ($array as $key => $list) {
                if ($list instanceof DateTime) {
                    $dumy[$key] = $list->format('Ymd His');
                } elseif (gettype($list) == 'boolean') {
                    if ($list) {
                        $dumy[$key] = 'true';
                    } else {
                        $dumy[$key] = 'false';
                    }
                } else {
                    $dumy[$key] = (string)$list;
                }
            }

            array_push($actual, $dumy);
        }
        return $actual;
    }

    public function testSelect()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $cyokkaKeikakuData = clone $this->modelData;
        $cyokkaKeikakuData->kb_nendo = '2014K';
        $cyokkaKeikakuData->cd_bumon = 'IBB10';
        $actual = $this->modelDb->select($cyokkaKeikakuData);

        $stmt = $this->executeQuery(
            "SELECT * 
            FROM {$this->tablename}
            WHERE kb_nendo = '2014K'
                AND cd_bumon = 'IBB10'
            ",
            [],
            $this->pdo,
        );

        $dataset = $stmt->fetchAll();

        $this->assertEquals(count($dataset), count($actual));

        for (
            $i = 0, $length = count($actual);
            $i < $length;
            $i++
        ) {
            $this->assertEquals(
                $dataset[$i],
                ($actual[$i])->toArray()
            );
        }
    }
}
