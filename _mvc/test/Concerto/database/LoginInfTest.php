<?php

declare(strict_types=1);

namespace test\Concerto;

use test\Concerto\AbstractSqliteTestCase;
use Concerto\database\LoginInf;
use Concerto\database\LoginInfData;
use DateTime;
use PDO;
use League\Csv\Reader;

class LoginInfTest extends AbstractSqliteTestCase
{
    private $modelDb;
    private $modelData;
    private $tablename = 'public_login_inf';
    private $file;

    protected function setUp(): void
    {
        $this->initPdo();
        $this->modelDb = new LoginInf($this->pdo);
        $this->modelData = new LoginInfData();

        //to correspond Sqlite
        $this->setPrivateProperty($this->modelDb, 'schema', $this->tablename);
        $this->setPrivateProperty($this->modelDb, 'name', $this->tablename);

        $this->setupTable($this->modelDb, $this->modelData);

        $this->file =
            __DIR__ . DIRECTORY_SEPARATOR .
            implode(
                DIRECTORY_SEPARATOR,
                ['data', 'login_inf.csv'],
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

        $order = null;

        //part
        $data = clone $this->modelData;
        $data->cd_tanto = '99601ITC';
        $rows = $this->modelDb->select($data, $order);

        $expect = array_reverse($this->changeDatasetToArray());
        $actual = $this->changeDatabaseToArray($rows);
        array_multisort($expect);
        array_multisort($actual);

        $this->assertEquals(array($expect[0]), $actual);

        //full
        $data = clone $this->modelData;
        $rows = $this->modelDb->select($data, $order);

        $expect = array_reverse($this->changeDatasetToArray());
        $actual = $this->changeDatabaseToArray($rows);
        array_multisort($expect);
        array_multisort($actual);

        $this->assertEquals($expect, $actual);
    }
}
