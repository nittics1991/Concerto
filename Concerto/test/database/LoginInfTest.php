<?php

declare(strict_types=1);

namespace Concerto\test;

use PHPUnit\DbUnit\DataSet\CsvDataSet as PHPUnit_Extensions_Database_DataSet_CsvDataSet;
use Concerto\test\abstractSqliteTestCase;
use Concerto\database\LoginInf;
use Concerto\database\LoginInfData;
use DateTime;
use PDO;

class LoginInfTest extends abstractSqliteTestCase
{
    private $loginInf;
    private $loginInfData;
    private $table = 'public_login_inf';

    protected function getDataSet()
    {
        $dataSet = new PHPUnit_Extensions_Database_DataSet_CsvDataSet();
        $dataSet->addTable($this->table, dirname(__FILE__) . "\\data\\login_inf.csv");
        return $dataSet;
    }

    protected function setUp(): void
    {
        $this->initPdo();
        $this->loginInf = new LoginInf(self::$pdo);
        $this->loginInfData = new LoginInfData();

        //to correspond Sqlite
        $this->setPrivateProperty($this->loginInf, 'schema', $this->table);
        $this->setPrivateProperty($this->loginInf, 'name', $this->table);

        $this->setupTable($this->loginInf, $this->loginInfData);
        parent::setUp();
    }

    public function testRowCount()
    {
        $this->assertEquals(12, $this->getConnection()->getRowCount($this->table));
    }

    /**
    * データセットからデータを配列で返す
    *
    */
    public function changeDatasetToArray()
    {
        $dataset_array = (array)$this->getDataSet()->getTable($this->table);
        return $dataset_array[array_keys($dataset_array)[1]];
    }

    /**
    * ModelData配列を配列に変換する
    *
    */
    public function changeDatabaseToArray($objects)
    {
//      $this->markTestIncomplete();

        $actual = array();
        foreach ($objects as $obj) {
            $array = $obj->toArray();

            $dumy = array();
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
//      $this->markTestIncomplete();

        $order = null;

        //part
        $data = new LoginInfData();
        $data->cd_tanto = '99601ITC';
        $rows = $this->loginInf->select($data, $order);

        $expect = array_reverse($this->changeDatasetToArray());
        $actual = $this->changeDatabaseToArray($rows);
        array_multisort($expect);
        array_multisort($actual);

        $this->assertEquals(array($expect[0]), $actual);

        //full
        $data = new LoginInfData();
        $rows = $this->loginInf->select($data, $order);

        $expect = array_reverse($this->changeDatasetToArray());
        $actual = $this->changeDatabaseToArray($rows);
        array_multisort($expect);
        array_multisort($actual);

        $this->assertEquals($expect, $actual);
    }

    public function testDeletePastDate()
    {
        $this->markTestIncomplete();

        $order = null;

        $today = new DateTime();
        $data_las = new DateTime('20141211');
        $interval = $data_las->diff($today);

        //141205,141206の2レコード削除
        $this->loginInf->deletePastDate((int)$interval->format('%d') + 5);

        $data = new LoginInfData();
        $rows = $this->loginInf->select($data, $order);

        $expect = array_reverse($this->changeDatasetToArray());
        $actual = $this->changeDatabaseToArray($rows);
        array_multisort($expect);
        array_multisort($actual);

        $expect_new = array();
        for (
            $i = 2, $length = count($expect);
            $i < $length;
            $i++
        ) {
            $expect_new[] = $expect[$i];
        }

        //$this->assertEquals($expect_new, $actual);
    }
}
