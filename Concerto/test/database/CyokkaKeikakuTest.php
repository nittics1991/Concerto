<?php

declare(strict_types=1);

namespace Concerto\test\database;

use Concerto\test\abstractSqliteTestCase;
use PHPUnit\DbUnit\DataSet\CsvDataSet as PHPUnit_Extensions_Database_DataSet_CsvDataSet;
use Concerto\database\CyokkaKeikaku;
use Concerto\database\CyokkaKeikakuData;

class CyokkaKeikakuTest extends abstractSqliteTestCase
{
    private $cyokkaKeikaku;
    private $cyokkaKeikakuData;
    private $table = 'public_cyokka_keikaku';

    protected function getDataSet()
    {
        $dataSet = new PHPUnit_Extensions_Database_DataSet_CsvDataSet();
        $dataSet->addTable($this->table, dirname(__FILE__) . "\\data\\cyokka_keikaku.csv");
        return $dataSet;
    }

    protected function setUp(): void
    {
        $this->initPdo();
        $this->cyokkaKeikaku = new CyokkaKeikaku(self::$pdo);
        $this->cyokkaKeikakuData = new CyokkaKeikakuData();

        //to correspond Sqlite
        $this->setPrivateProperty($this->cyokkaKeikaku, 'schema', $this->table);
        $this->setPrivateProperty($this->cyokkaKeikaku, 'name', $this->table);

        $this->setupTable($this->cyokkaKeikaku, $this->cyokkaKeikakuData);
        parent::setUp();
    }

    public function testRowCount()
    {
        $this->assertEquals(6, $this->getConnection()->getRowCount($this->table));
    }

    public function testSelect()
    {
//      $this->markTestIncomplete();

        $cyokkaKeikakuData = clone $this->cyokkaKeikakuData;
        $cyokkaKeikakuData->kb_nendo = '2014K';
        $cyokkaKeikakuData->cd_bumon = 'IBB10';
        $actual = $this->cyokkaKeikaku->select($cyokkaKeikakuData);

        $expect = $this->getConnection()
            ->createQueryTable(
                $this->table,
                "SELECT * 
                FROM {$this->table}
                WHERE kb_nendo = '2014K'
                    AND cd_bumon = 'IBB10'
                "
            )
        ;

        $this->assertEquals($expect->getRowCount(), count($actual));

        for (
            $i = 0, $length = count($actual);
            $i < $length;
            $i++
        ) {
            $this->assertEquals($expect->getRow($i), ($actual[$i])->toArray());
        }
    }
}
