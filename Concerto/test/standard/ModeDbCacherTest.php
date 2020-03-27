<?php

declare(strict_types=1);

namespace Concerto\test\standard;

use Concerto\test\ConcertoTestCase;
use PHPUnit\DbUnit\DataSet\YamlDataSet as PHPUnit_Extensions_Database_DataSet_YamlDataSet;
use Concerto\test\abstractDatabaseTestCase;
use Concerto\standard\ModelDbCacher;
use Concerto\standard\ModelData;
use Concerto\standard\ModelDb;

class _ModelDbCacher extends ModelDb
{
    protected $schema = 'test._modeldbcacher';
}

class _ModelDataCacher extends ModelData
{
    protected static $schema = array(
        "b_data" => parent::BOOLEAN
        , "i_data" => parent::INTEGER
        , "f_data" => parent::FLOAT
        , "d_data" => parent::DOUBLE
        , "s_data" => parent::STRING
        , "t_data" => parent::DATETIME
    );
}


///////////////////////////////////////////////////////////////////////////////////////////////////


class ModelDbCacherTest extends abstractDatabaseTestCase
{
    private $tablename;
    private $file;
    
    protected function getDataSet()
    {
        $this->tablename = 'test._modeldbcacher';
        
        $this->file = __DIR__ . '\\data\\modelDbCacher\\_modeldbcacher.yml';
        $dataSet = new PHPUnit_Extensions_Database_DataSet_YamlDataSet($this->file);
        return $dataSet;
    }
    
    /**
    *   @test
    *
    **/
    public function checkSetupTable()
    {
//      $this->markTestIncomplete();
        
        $datasetRowCount = $this->getDataSet()
            ->getTable($this->tablename)
            ->getRowCount();
        $tableRowCount = $this->getConnection()
            ->getRowCount($this->tablename);
        $this->assertEquals($datasetRowCount, $tableRowCount);
    }
    
    /**
    *   @test
    *
    **/
    public function addInsertData()
    {
//      $this->markTestIncomplete();
        
        $obj = new ModelDbCacher(static::$pdo);
        
        $data = new _ModelDataCacher();
        
        $data1 = clone $data;
        $data1->i_data = 10;
        $obj->addInsertData($data1);
        
        $data2 = clone $data;
        $data2->f_data = 10;
        $obj->addInsertData($data2);
        
        $expect = [$data1, $data2];
        
        $this->assertEquals(
            $expect,
            $this->getPrivateProperty($obj, 'inserts')
        );
    }
    
    /**
    *   @test
    *
    **/
    public function addUpdateData()
    {
//      $this->markTestIncomplete();
        
        $obj = new ModelDbCacher(static::$pdo);
        
        $data = new _ModelDataCacher();
        $where = clone $data;
        
        $where1 = clone $data;
        $where1->i_data = 10;
        $data1 = clone $data;
        $data1->s_data = "start";
        $obj->addUpdateData($data1, $where1);
        
        $where2 = clone $data;
        $where2->f_data = 10;
        $data2 = clone $data;
        $data2->s_data = "end";
        $obj->addUpdateData($data2, $where2);
        
        $expect = [[$data1, $where1], [$data2, $where2]];
        
        $this->assertEquals(
            $expect,
            $this->getPrivateProperty($obj, 'updates')
        );
    }
    
    /**
    *   @test
    *
    **/
    public function addDeleteData()
    {
//      $this->markTestIncomplete();
        
        $obj = new ModelDbCacher(static::$pdo);
        
        $data = new _ModelDataCacher();
        
        $where1 = clone $data;
        $where1->i_data = 10;
        $where1->s_data = "start";
        $obj->addInsertData($where1);
        
        $where2 = clone $data;
        $where2->f_data = 10;
        $where2->s_data = "end";
        $obj->addInsertData($where2);
        
        $expect = [$where1, $where2];
        
        $this->assertEquals(
            $expect,
            $this->getPrivateProperty($obj, 'inserts')
        );
    }
    
    /**
    *   @test
    *
    **/
    public function save1()
    {
//      $this->markTestIncomplete();
        
        $data = new _ModelDataCacher();
        $where = clone $data;
        
        //insert
        $obj = new ModelDbCacher(static::$pdo, new _ModelDbCacher(static::$pdo));
        $data1 = clone $data;
        $data1->i_data = 100;
        $obj->addInsertData($data1);
        
        $data2 = clone $data;
        $data2->f_data = 200;
        $obj->addInsertData($data2);
        
        $obj->save();
        
        $tableData = $this->getConnection()
            ->createQueryTable(
                'test._modeldbcacher',
                'SELECT * FROM test._modeldbcacher WHERE i_data=100 OR f_data = 200'
            );
        $this->assertEquals(2, $tableData->getRowCount());
        
        //update
        $obj = $obj->createCacher(new _ModelDbCacher(static::$pdo));
        $where1 = clone $data;
        $where1->i_data = 100;
        $data1 = clone $data;
        $data1->s_data = "start";
        $obj->addUpdateData($data1, $where1);
        
        $where2 = clone $data;
        $where2->f_data = 100;
        $data2 = clone $data;
        $data2->s_data = "end";
        $obj->addUpdateData($data2, $where2);
        
        $obj->save();
        
        $tableData = $this->getConnection()
            ->createQueryTable(
                'test._modeldbcacher',
                'SELECT * FROM test._modeldbcacher WHERE i_data=100'
            );
        $this->assertEquals(1, $tableData->getRowCount());
        $this->assertEquals('start', $tableData->getValue(0, 's_data'));
        
        //insert,update,delete
        $obj = $obj->createCacher(new _ModelDbCacher(static::$pdo));
        
        $data1 = clone $data;
        $data1->i_data = 100;
        $data1->s_data = 'replace';
        $obj->addInsertData($data1);
        
        $where2 = clone $data;
        $where2->f_data = 200;
        $data2 = clone $data;
        $data2->s_data = "end";
        $obj->addUpdateData($data2, $where2);
        
        $where3 = clone $data;
        $where3->i_data = 100;
        $where3->s_data = 'start';
        $obj->addDeleteData($where3);
        
        $obj->save();
        
        $tableData = $this->getConnection()
            ->createQueryTable(
                'test._modeldbcacher',
                'SELECT * FROM test._modeldbcacher WHERE 1 = 1'
            );
        $this->assertEquals(4, $tableData->getRowCount());
        
        $tableData = $this->getConnection()
            ->createQueryTable(
                'test._modeldbcacher',
                'SELECT * FROM test._modeldbcacher WHERE i_data = 100'
            );
        $this->assertEquals('replace', $tableData->getValue(0, 's_data'));
        
        $tableData = $this->getConnection()
            ->createQueryTable(
                'test._modeldbcacher',
                'SELECT * FROM test._modeldbcacher WHERE f_data = 200'
            );
        $this->assertEquals('end', $tableData->getValue(0, 's_data'));
    }
}
