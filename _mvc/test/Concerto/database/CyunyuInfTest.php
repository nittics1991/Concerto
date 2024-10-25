<?php

declare(strict_types=1);

namespace test\Concerto\database;

use test\Concerto\AbstractSqliteTestCase;
use Concerto\database\CyunyuInf;
use Concerto\database\CyunyuInfData;
use PDO;
use League\Csv\Reader;

class CyunyuInfTest extends AbstractSqliteTestCase
{
    private $modelDb;
    private $modelData;
    private $tablename = 'public_cyunyu_inf';
    private $file;

    protected function setUp(): void
    {
        $this->initPdo();
        $this->modelDb = new CyunyuInf($this->pdo);
        $this->modelData = new CyunyuInfData();

        //to correspond Sqlite
        $this->setPrivateProperty($this->modelDb, 'schema', $this->tablename);
        $this->setPrivateProperty($this->modelDb, 'name', $this->tablename);

        $this->setupTable($this->modelDb, $this->modelData);

        $this->file =
            __DIR__ . DIRECTORY_SEPARATOR .
            implode(
                DIRECTORY_SEPARATOR,
                ['data', 'cyunyu_inf.csv'],
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

    public function testGetMaxNoSeq()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $no_cyu = 'ICH30003';
        $no_ko = 'CH001';

        $this->assertEquals(2, $this->modelDb->getMaxNoSeq($no_cyu, $no_ko));
    }

    //more methods
}
