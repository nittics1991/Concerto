<?php

declare(strict_types=1);

namespace Concerto\test\standard;

use Concerto\test\ConcertoTestCase;
use PHPUnit\DbUnit\DataSet\YamlDataSet as PHPUnit_Extensions_Database_DataSet_YamlDataSet;
use Composer_Autoload_ClassLoader;
use Concerto\test\abstractDatabaseTestCase;
use Concerto\standard\ArrayUtil;
use Concerto\standard\ModelData;
use Concerto\standard\ModelDbTree;
use Symfony\Component\yaml\Yaml;
use Closure;

class _ModelDbTree extends ModelDbTree
{
    protected $schema = 'test._modeldbTree';
    protected $root = null;
    protected $primarykey = 'cd_id';
    protected $parent = 'cd_parent';
    protected $depth = 'no_depth';
    protected $path = 'cd_path';
}

class _ModelDataTree extends ModelData
{
    protected static $schema = array(
        "cd_id" => parent::STRING
        , "cd_parent" => parent::STRING
        , "no_data" => parent::INTEGER
        , "nm_data" => parent::STRING
        , "no_depth" => parent::INTEGER     //virtual column
        , "cd_path" => parent::STRING   //virtual column
    );
}

class _BatModelDbTree extends ModelDbTree
{
//  protected $schema = 'test._modeldbTree';
//  protected $root = null;
//  protected $primarykey = 'cd_id';
//  protected $depth = 'no_depth';
//  protected $path = 'cd_path';
}

class _BadModelDataTree extends ModelData
{
    protected static $schema = array(
        "id" => parent::STRING
        , "parent" => parent::STRING
        , "data" => parent::INTEGER
        , "data" => parent::STRING
        , "depth" => parent::INTEGER    //virtual column
        , "path" => parent::STRING  //virtual column
    );
}


//root()で使用
class _ModelDbTreeData extends ModelData
{
    protected static $schema = array(
        "cd_id" => parent::STRING
        , "cd_parent" => parent::STRING
        , "no_data" => parent::INTEGER
        , "nm_data" => parent::STRING
        , "no_depth" => parent::INTEGER     //virtual column
        , "cd_path" => parent::STRING   //virtual column
    );
}



class _ModelDbTreeTest extends abstractDatabaseTestCase
{
    //private methodテストの為public
    public $class;
    public $path;
    public $file;
    public $modelData;
    
    protected function getDataSet()
    {
        $this->path = __DIR__ . '\\data\\modelDbTree\\';
        $this->file = "{$this->path}_modeldbtree.yml";
        $dataSet = new PHPUnit_Extensions_Database_DataSet_YamlDataSet($this->file);
        return $dataSet;
    }
    
    protected function setUp(): void
    {
        parent::setUp();
        $this->class = new _ModelDbTree(static::$pdo);
        
        //isValidGroupClause追加に伴うVALID強化でDataのclass名が固定化された
//      $this->modelData = new _ModelDataTree();

        $this->modelData = new _ModelDbTreeData();
    }
    
    public function testRowCount()
    {
//      $this->markTestIncomplete();
        
        $this->assertEquals(13, $this->getConnection()->getRowCount('test._modeldbtree'));
    }
    
    /**
    *   @test
    **/
    public function checkColumnName()
    {
//      $this->markTestIncomplete();
        
        Closure::bind(function () {
            $obj = clone $this->modelData;
            $this->assertEquals(true, $this->class->checkColumnName($obj));
            
            $obj = new _BadModelDataTree();
            $this->assertEquals(false, $this->class->checkColumnName($obj));
        }, $this, 'Concerto\test\standard\_ModelDbTree')->__invoke();
    }
    
    /**
    *   @test
    *
    **/
    public function detail()
    {
//      $this->markTestIncomplete();
        
        $where = clone $this->modelData;
        $where->cd_id = $cd_id = '560700';
        $result = $this->class->detail($where);
        unset($actual);
        
        foreach ($result as $obj) {
            $actual[] = $obj->toArray();
        }
        
        $expect = (new PHPUnit_Extensions_Database_DataSet_YamlDataSet("{$this->path}detail.yml"))
            ->getTable('test._modeldbtree')
            ->getRow(0);
            
        $this->assertEquals(1, count($actual));
        $this->assertEquals($expect, $actual[0]);
        
        //ID存在しない
        $where = clone $this->modelData;
        $where->cd_id = $cd_id = '999999';
        $result = $this->class->detail($where);
        $this->assertEmpty($result);
    }
    
    /**
    *   @test
    *
    **/
    public function parent()
    {
//      $this->markTestIncomplete();
        
        $where = clone $this->modelData;
        $where->cd_id = $cd_id = '560700';
        $result = $this->class->parent($where);
        unset($actual);
        
        foreach ($result as $obj) {
            $actual[] = $obj->toArray();
        }
        
        $expect = (new PHPUnit_Extensions_Database_DataSet_YamlDataSet("{$this->path}parent.yml"))
            ->getTable('test._modeldbtree')
            ->getRow(0);
            
        $this->assertEquals(1, count($actual));
        $this->assertEquals($expect, $actual[0]);
        
        //ID存在しない
        $where = clone $this->modelData;
        $where->cd_id = $cd_id = '999999';
        $result = $this->class->parent($where);
        $this->assertEmpty($result);
        
        //parent存在しない
        $where = clone $this->modelData;
        $where->cd_id = $cd_id = "000000";
        $result = $this->class->parent($where);
        $this->assertEmpty($result);
    }
    
    /**
    *   @test
    */
    public function parentException()
    {
//      $this->markTestIncomplete();
        
        $this->expectException(\RuntimeException::class);
        $where = clone $this->modelData;
        $where->cd_id = $cd_id = '560000';
        $class = new _BatModelDbTree(static::$pdo);
        $result = $class->parent($where);
    }
    
    /**
    *   @test
    *
    **/
    public function children()
    {
//      $this->markTestIncomplete();
        
        $where = clone $this->modelData;
        $where->cd_id = $cd_id = '560700';
        $result = $this->class->children($where);
        
        $this->assertEquals(2, count($result));
        
        $dataset = (new PHPUnit_Extensions_Database_DataSet_YamlDataSet("{$this->path}children.yml"))
            ->getTable('test._modeldbtree');
        
        $i = 0;
        
        foreach ($result as $obj) {
            $actual = $obj->toArray();
            $expect = $dataset->getRow($i);
            $this->assertEquals($expect, $actual);
            
            $memory[] = $expect;
            
            $i++;
        }
        
        //逆順
        $result = $this->class->children($where, 'cd_id DESC');
        $reverse = array_reverse($memory);
        $i = 0;
        
        foreach ($result as $obj) {
            $actual = $obj->toArray();
            $expect = $reverse[$i];
            $this->assertEquals($expect, $actual);
            $i++;
        }
        
        //ID存在しない
        $where = clone $this->modelData;
        $where->cd_id = $cd_id = '999999';
        $result = $this->class->children($where);
        $this->assertEmpty($result);
        
        //children存在しない
        $where = clone $this->modelData;
        $where->cd_id = $cd_id = '560807';
        $result = $this->class->children($where);
        $this->assertEmpty($result);
    }
    
    /**
    *   @test
    */
    public function childrenException()
    {
//      $this->markTestIncomplete();
        
        $this->expectException(\RuntimeException::class);
        $where = clone $this->modelData;
        $where->cd_id = $cd_id = '560000';
        $class = new _BatModelDbTree(static::$pdo);
        $result = $class->children($where);
    }
    
    /**
    *   @test
    */
    public function childrenException2()
    {
//      $this->markTestIncomplete();
        
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('data type error:AAA desc');
        $where = clone $this->modelData;
        $where->cd_id = $cd_id = '560700';
        $result = $this->class->children($where, 'AAA desc');
    }
    
    /**
    *   @test
    *
    **/
    public function sibling()
    {
//      $this->markTestIncomplete();
        
        $where = clone $this->modelData;
        $where->cd_id = $cd_id = '560700';
        $result = $this->class->sibling($where);
        
        $this->assertEquals(4, count($result));
        
        $dataset = (new PHPUnit_Extensions_Database_DataSet_YamlDataSet("{$this->path}sibling.yml"))
            ->getTable('test._modeldbtree');
        $i = 0;
        
        foreach ($result as $obj) {
            $actual = $obj->toArray();
            $expect = $dataset->getRow($i);
            $this->assertEquals($expect, $actual);
            
            $memory[] = $expect;
            
            $i++;
        }
        
        //逆順
        $result = $this->class->sibling($where, 'cd_id DESC');
        $reverse = array_reverse($memory);
        $i = 0;
        
        foreach ($result as $obj) {
            $actual = $obj->toArray();
            $expect = $reverse[$i];
            $this->assertEquals($expect, $actual);
            $i++;
        }
        
        //ID存在しない
        $where = clone $this->modelData;
        $where->cd_id = $cd_id = '999999';
        $result = $this->class->sibling($where);
        $this->assertEmpty($result);
        
        //sibling存在しない
        $where = clone $this->modelData;
        $where->cd_id = $cd_id = '000000';
        $result = $this->class->sibling($where);
        $this->assertEmpty($result);
    }
    
    /**
    *   @test
    */
    public function siblingException2()
    {
//      $this->markTestIncomplete();
        
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('data type error:AAA desc');
        $where = clone $this->modelData;
        $where->cd_id = $cd_id = '560700';
        $result = $this->class->sibling($where, 'AAA desc');
    }
    
    /**
    *   @test
    *
    **/
    public function ancestor()
    {
//      $this->markTestIncomplete();
        
        $where = clone $this->modelData;
        $where->cd_id = $cd_id = '560700';
        $result = $this->class->ancestor($where);
        
        $this->assertEquals(2, count($result));
        
        $dataset = (new PHPUnit_Extensions_Database_DataSet_YamlDataSet("{$this->path}ancestor.yml"))
            ->getTable('test._modeldbtree');
        $i = 0;
        
        foreach ($result as $obj) {
            $actual = $obj->toArray();
            $expect = $dataset->getRow($i);
            $this->assertEquals($expect, $actual);
            
            $memory[] = $expect;
            
            $i++;
        }
        
        //逆順
        $result = $this->class->ancestor($where, 'cd_id DESC');
        $reverse = array_reverse($memory);
        $i = 0;
        
        foreach ($result as $obj) {
            $actual = $obj->toArray();
            $expect = $reverse[$i];
            $this->assertEquals($expect, $actual);
            $i++;
        }
        
        //枝切りlimit
        $result = $this->class->ancestor($where, null, 1);
        
        $i = 0;
        $expect_path = array('000000', '560000');
        $path = '';
        
        foreach ($result as $obj) {
            $actual = $obj->toArray();
            $expect = $dataset->getRow($i);
            
            $path .= "/{$expect_path[$i]}";
            
            $expect = array_merge(
                $expect,
                array('cd_path' => $path),
                array('no_depth' => $i + 1)
            );
            
            $this->assertEquals($expect, $actual);
            $i++;
        }
        
        //ID存在しない
        $where = clone $this->modelData;
        $where->cd_id = $cd_id = '999999';
        $result = $this->class->ancestor($where);
        $this->assertEmpty($result);
    }
    
    /**
    *   @test
    */
    public function ancestorException()
    {
//      $this->markTestIncomplete();
        
        $this->expectException(\RuntimeException::class);
        $where = clone $this->modelData;
        $where->cd_id = $cd_id = '560000';
        $class = new _BatModelDbTree(static::$pdo);
        $result = $class->ancestor($where);
    }
    
    /**
    *   @test
    */
    public function ancestorException2()
    {
//      $this->markTestIncomplete();
        
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('limit is integer of 0 or more');
        $where = clone $this->modelData;
        $where->cd_id = $cd_id = '560700';
        $result = $this->class->ancestor($where, 'cd_id', -1);
    }
    
    /**
    *   @test
    *
    **/
    public function descendant()
    {
//      $this->markTestIncomplete();
        
        $where = clone $this->modelData;
        $where->cd_id = $cd_id = '560000';
        $result = $this->class->descendant($where);
        
        $this->assertEquals(11, count($result));
        
        $dataset = (new PHPUnit_Extensions_Database_DataSet_YamlDataSet("{$this->path}descentant.yml"))
            ->getTable('test._modeldbtree');
        $i = 0;
        
        foreach ($result as $obj) {
            $actual = $obj->toArray();
            $expect = $dataset->getRow($i);
            $this->assertEquals($expect, $actual);
            
            $memory[] = $expect;
            
            $i++;
        }
        
        //逆順
        $result = $this->class->descendant($where, 'cd_id DESC');
        $reverse = array_reverse($memory);
        $i = 0;
        
        foreach ($result as $obj) {
            $actual = $obj->toArray();
            $expect = $reverse[$i];
            $this->assertEquals($expect, $actual);
            $i++;
        }
        
        //枝切りlimit
        $result = $this->class->descendant($where, null, 1);
        
        $dataset = (new PHPUnit_Extensions_Database_DataSet_YamlDataSet("{$this->path}descentantLimit.yml"))
            ->getTable('test._modeldbtree');
        $i = 0;
        
        foreach ($result as $obj) {
            $actual = $obj->toArray();
            $expect = $dataset->getRow($i);
            $this->assertEquals($expect, $actual);
            $i++;
        }
        
        //ID存在しない
        $where = clone $this->modelData;
        $where->cd_id = $cd_id = '999999';
        $result = $this->class->descendant($where);
        $this->assertEmpty($result);
    }
    
    /**
    *   @test
    */
    public function descendantException()
    {
//      $this->markTestIncomplete();
        
        $this->expectException(\RuntimeException::class);
        $where = clone $this->modelData;
        $where->cd_id = $cd_id = '560000';
        $class = new _BatModelDbTree(static::$pdo);
        $result = $class->descendant($where);
    }
    
    /**
    *   @test
    *
    **/
    public function numberOfChildren()
    {
//      $this->markTestIncomplete();
        
        $where = clone $this->modelData;
        $where->cd_id = $cd_id = '560000';
        $this->assertEquals(5, $this->class->numberOfChildren($where));
        
        $where->cd_id = $cd_id = '560807';
        $this->assertEquals(0, $this->class->numberOfChildren($where));
    }
    
    /**
    *   @test
    *
    **/
    public function numberOfSibling()
    {
//      $this->markTestIncomplete();
        
        $where = clone $this->modelData;
        $where->cd_id = $cd_id = '000000';
        $this->assertEquals(1, $this->class->numberOfSibling($where));
        
        $where->cd_id = $cd_id = '560807';
        $this->assertEquals(4, $this->class->numberOfSibling($where));
    }
    
    /**
    *   @test
    *
    **/
    public function isLeaf()
    {
//      $this->markTestIncomplete();
        
        $where = clone $this->modelData;
        $where->cd_id = $cd_id = '560800';
        $this->assertEquals(false, $this->class->isLeaf($where));
        
        $where->cd_id = $cd_id = '560807';
        $this->assertEquals(true, $this->class->isLeaf($where));
    }
    
    /**
    *   @test
    *
    **/
    public function root()
    {
//      $this->markTestIncomplete();
        
        $expect = (new PHPUnit_Extensions_Database_DataSet_YamlDataSet("{$this->path}root.yml"))
            ->getTable('test._modeldbtree')
            ->getRow(0);
        
        $result = $this->class->root();
        $obj = $result[0];
        $actual = $obj->toArray();
        $this->assertEquals($expect, $actual);
    }
    
    /**
    *   @test
    *
    **/
    public function treePath()
    {
//      $this->markTestIncomplete();
        
        $where = clone $this->modelData;
        $where->cd_id = $cd_id = '560802';
        $result = $this->class->treePath($where);
        
        $this->assertEquals(4, count($result));
        
        $dataset = (new PHPUnit_Extensions_Database_DataSet_YamlDataSet("{$this->path}treePath.yml"))
            ->getTable('test._modeldbtree');
        $i = 0;
        
        foreach ($result as $obj) {
            $actual = $obj->toArray();
            $expect = $dataset->getRow($i);
            $this->assertEquals($expect, $actual);
            $i++;
        }
    }
    
    /**
    *   @test
    *
    **/
    public function searchTree()
    {
//      $this->markTestIncomplete();
        
        //深さ優先ASC limit無し
        $where = clone $this->modelData;
        $where->cd_id = $cd_id = '560000';
        $result = $this->class->searchTree($where);
        
        $this->assertEquals(12, count($result));
        
        $dataset = (new PHPUnit_Extensions_Database_DataSet_YamlDataSet("{$this->path}searchTreeDepthAsc.yml"))
            ->getTable('test._modeldbtree');
        $i = 0;
        
        foreach ($result as $obj) {
            $actual = $obj->toArray();
            $expect = $dataset->getRow($i);
            $this->assertEquals($expect, $actual);
            $i++;
        }
        
        //深さ優先ASC limit有り
        $result = $this->class->searchTree($where, 'depth', 'cd_id', 2);
        
        $this->assertEquals(6, count($result));
        
        $dataset = (new PHPUnit_Extensions_Database_DataSet_YamlDataSet("{$this->path}searchTreeDepthAscLimit.yml"))
            ->getTable('test._modeldbtree');
        $i = 0;
        
        foreach ($result as $obj) {
            $actual = $obj->toArray();
            $expect = $dataset->getRow($i);
            $this->assertEquals($expect, $actual);
            $i++;
        }
        
        //深さ優先DESC limit有り
        $result = $this->class->searchTree($where, 'depth', 'cd_id DESC', 3);
        
        $this->assertEquals(12, count($result));
        
        $dataset = (new PHPUnit_Extensions_Database_DataSet_YamlDataSet("{$this->path}searchTreeDepthDesc.yml"))
            ->getTable('test._modeldbtree');
        $i = 0;
        
        foreach ($result as $obj) {
            $actual = $obj->toArray();
            $expect = $dataset->getRow($i);
            $this->assertEquals($expect, $actual);
            $i++;
        }
        
        //幅優先ASC limit無し
        $result = $this->class->searchTree($where, 'breadth', 'cd_id');
        
        $this->assertEquals(12, count($result));
        
        $dataset = (new PHPUnit_Extensions_Database_DataSet_YamlDataSet("{$this->path}searchTreeBreadthAsc.yml"))
            ->getTable('test._modeldbtree');
        $i = 0;
        
        foreach ($result as $obj) {
            $actual = $obj->toArray();
            $expect = $dataset->getRow($i);
            $this->assertEquals($expect, $actual);
            $i++;
        }
        
        //幅優先ASC limit有り
        $result = $this->class->searchTree($where, 'breadth', 'cd_id', 2);
        
        $this->assertEquals(6, count($result));
        
        $i = 0;
        
        foreach ($result as $obj) {
            $actual = $obj->toArray();
            $expect = $dataset->getRow($i);
            $this->assertEquals($expect, $actual);
            $i++;
        }
        
        //幅優先DESC limit有り
        $result = $this->class->searchTree($where, 'breadth', 'cd_id DESC', 2);
        
        $this->assertEquals(6, count($result));
        
        $dataset = (new PHPUnit_Extensions_Database_DataSet_YamlDataSet("{$this->path}searchTreeBreadthDesc.yml"))
            ->getTable('test._modeldbtree');
        $i = 0;
        
        foreach ($result as $obj) {
            $actual = $obj->toArray();
            $expect = $dataset->getRow($i);
            $this->assertEquals($expect, $actual);
            $i++;
        }
        
        //ID存在しない
        $where = clone $this->modelData;
        $where->cd_id = $cd_id = '999999';
        $result = $this->class->searchTree($where);
        $this->assertEmpty($result);
    }
    
    /**
    *   @test
    */
    public function searchTreeException()
    {
//      $this->markTestIncomplete();
        
        $this->expectException(\RuntimeException::class);
        $where = clone $this->modelData;
        $where->cd_id = $cd_id = '560000';
        $class = new _BatModelDbTree(static::$pdo);
        $result = $class->searchTree($where);
    }
    
    /**
    *   @test
    */
    public function searchTreeException2()
    {
//      $this->markTestIncomplete();
        
        $this->expectException(\RuntimeException::class);
        $where = clone $this->modelData;
        $where->cd_id = $cd_id = '560000';
        $result = $this->class->searchTree($where, 'xxx');
    }
    
    /**
    *   @test
    *
    **/
    public function crud()
    {
//      $this->markTestIncomplete();
        
        //挿入
        $data = clone $this->modelData;
        $data->cd_id = '999';
        $data->cd_parent = '560800';
        $data->no_data = 21;
        $data->nm_data = 'Leaf1';
        
        $this->class->graft($data);
        
        //root挿入
        $data = clone $this->modelData;
        $data->cd_id = '888';
        $data->no_data = 22;
        $data->nm_data = 'root1';
        
        $this->class->graft($data);
        
        $dataset = (new PHPUnit_Extensions_Database_DataSet_YamlDataSet("{$this->path}crudGraft.yml"))
            ->getTable('test._modeldbtree');
        
        
        $table = $this->getConnection()->createQueryTable(
            'test._modeldbtree',
            "SELECT * FROM test._modeldbtree ORDER BY cd_id "
        );
        
        $this->assertEquals($dataset->getRowCount(), $table->getRowCount());
        
        // var_dump(get_class($dataset));
        
        // $this->assertTablesEqual($dataset, $table);
        $this->assertTrue($table->matches($dataset));
        
        //移動
        $target = clone $this->modelData;
        $target->cd_id = '999';
        $where = clone $this->modelData;
        $where->cd_id = '560002';
        
        $this->class->move($target, $where);
        
        $dataset = (new PHPUnit_Extensions_Database_DataSet_YamlDataSet("{$this->path}crudMove.yml"))
            ->getTable('test._modeldbtree');
        
        
        $table = $this->getConnection()->createQueryTable(
            'test._modeldbtree',
            "SELECT * FROM test._modeldbtree ORDER BY cd_id "
        );
        
        $this->assertEquals($dataset->getRowCount(), $table->getRowCount());
        
        // $this->assertTablesEqual($dataset, $table);
        $this->assertTrue($table->matches($dataset));
        
        //枝刈り
        $target = clone $this->modelData;
        $target->cd_id = '999';
        
        $this->class->prune($target);
        
        $dataset = (new PHPUnit_Extensions_Database_DataSet_YamlDataSet("{$this->path}crudPrune.yml"))
            ->getTable('test._modeldbtree');
        
        
        $table = $this->getConnection()->createQueryTable(
            'test._modeldbtree',
            "SELECT * FROM test._modeldbtree ORDER BY cd_id "
        );
        
        $this->assertEquals($dataset->getRowCount(), $table->getRowCount());
        
        // $this->assertTablesEqual($dataset, $table);
        $this->assertTrue($table->matches($dataset));
        
        //枝抜き
        $target = clone $this->modelData;
        $target->cd_id = '560700';
        
        $this->class->pull($target);
        
        $dataset = (new PHPUnit_Extensions_Database_DataSet_YamlDataSet("{$this->path}crudPull.yml"))
            ->getTable('test._modeldbtree');
        
        
        $table = $this->getConnection()->createQueryTable(
            'test._modeldbtree',
            "SELECT * FROM test._modeldbtree ORDER BY cd_id "
        );
        
        $this->assertEquals($dataset->getRowCount(), $table->getRowCount());
        
        // $this->assertTablesEqual($dataset, $table);
        $this->assertTrue($table->matches($dataset));
    }
}
