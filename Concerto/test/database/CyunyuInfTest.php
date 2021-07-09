<?php

declare(strict_types=1);

namespace Concerto\test\database;

use PHPUnit\DbUnit\DataSet\CsvDataSet as PHPUnit_Extensions_Database_DataSet_CsvDataSet;
use Concerto\test\abstractSqliteTestCase;
use Concerto\database\CyunyuInf;
use Concerto\database\CyunyuInfData;

class CyunyuInfTest extends abstractSqliteTestCase
{
    private $cyunyuInf;
    private $cyunyuInfData;
    private $table = 'public_cyunyu_inf';

    protected function getDataSet()
    {
        $dataSet = new PHPUnit_Extensions_Database_DataSet_CsvDataSet();
        $dataSet->addTable($this->table, dirname(__FILE__) . "\\data\\cyunyu_inf.csv");
        return $dataSet;
    }

    protected function setUp(): void
    {
        $this->initPdo();
        $this->cyunyuInf = new CyunyuInf(self::$pdo);
        $this->cyunyuInfData = new CyunyuInfData();

        //to correspond Sqlite
        $this->setPrivateProperty($this->cyunyuInf, 'schema', $this->table);
        $this->setPrivateProperty($this->cyunyuInf, 'name', $this->table);

        $this->setupTable($this->cyunyuInf, $this->cyunyuInfData);
        parent::setUp();
    }

    public function testRowCount()
    {
        $this->assertEquals(2, $this->getConnection()->getRowCount($this->table));
    }

    public function testGetMaxNoSeq()
    {
//      $this->markTestIncomplete();

        $no_cyu = 'ICH30003';
        $no_ko = 'CH001';

        $this->assertEquals(2, $this->cyunyuInf->getMaxNoSeq($no_cyu, $no_ko));
    }

    //more methods
}
