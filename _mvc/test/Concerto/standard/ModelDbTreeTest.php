<?php

declare(strict_types=1);

namespace test\Concerto\standard;

use test\Concerto\{
    ConcertoTestCase,
    DatabaseTestTrait,
};
use Concerto\standard\ArrayUtil;
use Concerto\standard\ModelData;
use Concerto\standard\ModelDbTree;

use Closure;
use PDO;

class _ModelDbTree extends ModelDbTree
{
    protected string $schema = 'test._modeldbTree';
    protected ?string $root = null;
    protected string $primarykey = 'cd_id';
    protected string $parent = 'cd_parent';
    protected string $depth = 'no_depth';
    protected string $path = 'cd_path';
}

// class _ModelDataTree extends ModelData
// {
    // protected static array $schema = [
        // "cd_id" => parent::STRING
        // , "cd_parent" => parent::STRING
        // , "no_data" => parent::INTEGER
        // , "nm_data" => parent::STRING
        // , "no_depth" => parent::INTEGER     //virtual column
        // , "cd_path" => parent::STRING   //virtual column
    // ];
// }

// class _BatModelDbTree extends ModelDbTree
// {
//  protected string $schema = 'test._modeldbTree';
//  protected $root = null;
//  protected $primarykey = 'cd_id';
//  protected $depth = 'no_depth';
//  protected $path = 'cd_path';
// }

class _BadModelDataTree extends ModelData
{
    protected static array $schema = [
        "id" => parent::STRING
        , "parent" => parent::STRING
        , "data" => parent::INTEGER
        , "data" => parent::STRING
        , "depth" => parent::INTEGER    //virtual column
        , "path" => parent::STRING  //virtual column
    ];
}

class _ModelDbTreeData extends ModelData
{
    protected static array $schema = [
        "cd_id" => parent::STRING
        , "cd_parent" => parent::STRING
        , "no_data" => parent::INTEGER
        , "nm_data" => parent::STRING
        , "no_depth" => parent::INTEGER     //virtual column
        , "cd_path" => parent::STRING   //virtual column
    ];
}

///////////////////////////////////////////////////////////////////

class ModelDbTreeTest extends ConcertoTestCase
{
    use DatabaseTestTrait;

    //private methodテストの為public
    public $obj;
    public $path;
    public $file;
    public $modelData;
    private $tablename = 'test._modeldbtree';

    protected function setUp(): void
    {
        global $DB_DSN;
        global $DB_USER;
        global $DB_PASSWD;
        global $DB_DBNAME;

        if (
            (
                extension_loaded("pdo-pgsql") ||
                extension_loaded("pgsql")
             ) &&
            !preg_match('/543[0,4,6]/', $GLOBALS['DB_DSN'])
        ) {
            throw new RuntimeException(
                "PostgreSQL DNS ERROR"
            );
        }

        $this->pdo = new PDO(
            $DB_DSN,
            $DB_USER,
            $DB_PASSWD,
            [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ],
        );

        $this->obj = new _ModelDbTree($this->pdo);
        $this->modelData = new _ModelDbTreeData();

        $this->truncateTable(
            'test._modeldbtree',
            $this->pdo,
        );

        $this->path =
            __DIR__ . DIRECTORY_SEPARATOR .
            implode(
                DIRECTORY_SEPARATOR,
                ['data', 'modelDbTree',],
            ) . DIRECTORY_SEPARATOR;

        $this->file = $this->path .
            '_modeldbtree.php';

        $dataset = $this->getDataSet();

        $this->importData(
            $this->tablename,
            $dataset['test._modeldbtree'],
            $this->pdo,
        );
    }

    protected function getDataSet()
    {
        $dataset = require($this->file);
        return $dataset;
    }

    public function testRowCount()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertEquals(13, $this->rowCount($this->tablename));
    }

    /**
    */
    #[Test]
    public function checkColumnName()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        Closure::bind(function () {
            $obj = clone $this->modelData;
            $this->assertEquals(true, $this->obj->checkColumnName($obj));

            $obj = new _BadModelDataTree();
            $this->assertEquals(false, $this->obj->checkColumnName($obj));
        }, $this, 'test\Concerto\standard\_ModelDbTree')->__invoke();
    }

    /**
    *
    */
    #[Test]
    public function detail()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $where = clone $this->modelData;
        $where->cd_id = $cd_id = '560700';
        $result = $this->obj->detail($where);
        unset($actual);

        foreach ($result as $obj) {
            $actual[] = $obj->toArray();
        }

        $dataset = require("{$this->path}detail.php");
        $expect = $dataset['test._modeldbtree'][0];

        $this->assertEquals(1, count($actual));
        $this->assertEquals($expect, $actual[0]);

        //ID存在しない
        $where = clone $this->modelData;
        $where->cd_id = $cd_id = '999999';
        $result = $this->obj->detail($where);
        $this->assertEmpty($result);
    }

    /**
    *
    */
    #[Test]
    public function parent()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $where = clone $this->modelData;
        $where->cd_id = $cd_id = '560700';
        $result = $this->obj->parent($where);
        unset($actual);

        foreach ($result as $obj) {
            $actual[] = $obj->toArray();
        }

        $dataset = require("{$this->path}parent.php");
        $expect = $dataset['test._modeldbtree'];

        $this->assertEquals(1, count($actual));
        $this->assertEquals($expect[0], $actual[0]);

        //ID存在しない
        $where = clone $this->modelData;
        $where->cd_id = $cd_id = '999999';
        $result = $this->obj->parent($where);
        $this->assertEmpty($result);

        //parent存在しない
        $where = clone $this->modelData;
        $where->cd_id = $cd_id = "000000";
        $result = $this->obj->parent($where);
        $this->assertEmpty($result);
    }

    /**
    *
    */
    #[Test]
    public function children()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $where = clone $this->modelData;
        $where->cd_id = $cd_id = '560700';
        $result = $this->obj->children($where);

        $this->assertEquals(2, count($result));

        $dataset = require("{$this->path}children.php");
        $expect_dataset = $dataset['test._modeldbtree'];

        $i = 0;

        foreach ($result as $obj) {
            $actual = $obj->toArray();
            $expect = $expect_dataset[$i];
            $this->assertEquals($expect, $actual);

            $memory[] = $expect;

            $i++;
        }

        //逆順
        $result = $this->obj->children($where, 'cd_id DESC');
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
        $result = $this->obj->children($where);
        $this->assertEmpty($result);

        //children存在しない
        $where = clone $this->modelData;
        $where->cd_id = $cd_id = '560807';
        $result = $this->obj->children($where);
        $this->assertEmpty($result);
    }

    /**
    */
    #[Test]
    public function childrenException2()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('data type error:AAA desc');
        $where = clone $this->modelData;
        $where->cd_id = $cd_id = '560700';
        $result = $this->obj->children($where, 'AAA desc');
    }

    /**
    *
    */
    #[Test]
    public function sibling()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $where = clone $this->modelData;
        $where->cd_id = $cd_id = '560700';
        $result = $this->obj->sibling($where);

        $this->assertEquals(4, count($result));

        $dataset = require("{$this->path}sibling.php");
        $expect_dataset = $dataset['test._modeldbtree'];

        $i = 0;

        foreach ($result as $obj) {
            $actual = $obj->toArray();
            $expect = $expect_dataset[$i];
            $this->assertEquals($expect, $actual);

            $memory[] = $expect;

            $i++;
        }

        //逆順
        $result = $this->obj->sibling($where, 'cd_id DESC');
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
        $result = $this->obj->sibling($where);
        $this->assertEmpty($result);

        //sibling存在しない
        $where = clone $this->modelData;
        $where->cd_id = $cd_id = '000000';
        $result = $this->obj->sibling($where);
        $this->assertEmpty($result);
    }

    /**
    */
    #[Test]
    public function siblingException2()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('data type error:AAA desc');
        $where = clone $this->modelData;
        $where->cd_id = $cd_id = '560700';
        $result = $this->obj->sibling($where, 'AAA desc');
    }

    /**
    *
    */
    #[Test]
    public function ancestor()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $where = clone $this->modelData;
        $where->cd_id = $cd_id = '560700';
        $result = $this->obj->ancestor($where);

        $this->assertEquals(2, count($result));

        $dataset = require("{$this->path}ancestor.php");
        $expect_dataset = $dataset['test._modeldbtree'];

        $i = 0;

        foreach ($result as $obj) {
            $actual = $obj->toArray();
            $expect = $expect_dataset[$i];
            $this->assertEquals($expect, $actual);

            $memory[] = $expect;

            $i++;
        }

        //逆順
        $result = $this->obj->ancestor($where, 'cd_id DESC');
        $reverse = array_reverse($memory);
        $i = 0;

        foreach ($result as $obj) {
            $actual = $obj->toArray();
            $expect = $reverse[$i];
            $this->assertEquals($expect, $actual);
            $i++;
        }

        //枝切りlimit
        $result = $this->obj->ancestor($where, null, 1);

        $i = 0;
        $expect_path = ['000000', '560000'];
        $path = '';

        foreach ($result as $obj) {
            $actual = $obj->toArray();
            $expect = $expect_dataset[$i];

            $path .= "/{$expect_path[$i]}";

            $expect = array_merge(
                $expect,
                ['cd_path' => $path],
                ['no_depth' => $i + 1],
            );

            $this->assertEquals($expect, $actual);
            $i++;
        }

        //ID存在しない
        $where = clone $this->modelData;
        $where->cd_id = $cd_id = '999999';
        $result = $this->obj->ancestor($where);
        $this->assertEmpty($result);
    }

    /**
    */
    #[Test]
    public function ancestorException2()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('limit is integer of 0 or more');
        $where = clone $this->modelData;
        $where->cd_id = $cd_id = '560700';
        $result = $this->obj->ancestor($where, 'cd_id', -1);
    }

    /**
    *
    */
    #[Test]
    public function descendant()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $where = clone $this->modelData;
        $where->cd_id = $cd_id = '560000';
        $result = $this->obj->descendant($where);

        $this->assertEquals(11, count($result));

        $dataset = require("{$this->path}descentant.php");
        $expect_dataset = $dataset['test._modeldbtree'];

        $i = 0;

        foreach ($result as $obj) {
            $actual = $obj->toArray();
            $expect = $expect_dataset[$i];
            $this->assertEquals($expect, $actual);

            $memory[] = $expect;

            $i++;
        }

        //逆順
        $result = $this->obj->descendant($where, 'cd_id DESC');
        $reverse = array_reverse($memory);
        $i = 0;

        foreach ($result as $obj) {
            $actual = $obj->toArray();
            $expect = $reverse[$i];
            $this->assertEquals($expect, $actual);
            $i++;
        }

        //枝切りlimit
        $result = $this->obj->descendant($where, null, 1);

        $dataset = require("{$this->path}descentantLimit.php");
        $expect_dataset = $dataset['test._modeldbtree'];

        $i = 0;

        foreach ($result as $obj) {
            $actual = $obj->toArray();
            $expect = $expect_dataset[$i];
            $this->assertEquals($expect, $actual);
            $i++;
        }

        //ID存在しない
        $where = clone $this->modelData;
        $where->cd_id = $cd_id = '999999';
        $result = $this->obj->descendant($where);
        $this->assertEmpty($result);
    }

    /**
    *
    */
    #[Test]
    public function numberOfChildren()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $where = clone $this->modelData;
        $where->cd_id = $cd_id = '560000';
        $this->assertEquals(5, $this->obj->numberOfChildren($where));

        $where->cd_id = $cd_id = '560807';
        $this->assertEquals(0, $this->obj->numberOfChildren($where));
    }

    /**
    *
    */
    #[Test]
    public function numberOfSibling()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $where = clone $this->modelData;
        $where->cd_id = $cd_id = '000000';
        $this->assertEquals(1, $this->obj->numberOfSibling($where));

        $where->cd_id = $cd_id = '560807';
        $this->assertEquals(4, $this->obj->numberOfSibling($where));
    }

    /**
    *
    */
    #[Test]
    public function isLeaf()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $where = clone $this->modelData;
        $where->cd_id = $cd_id = '560800';
        $this->assertEquals(false, $this->obj->isLeaf($where));

        $where->cd_id = $cd_id = '560807';
        $this->assertEquals(true, $this->obj->isLeaf($where));
    }

    /**
    *
    */
    #[Test]
    public function root()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $dataset = require("{$this->path}root.php");
        $expect = $dataset['test._modeldbtree'][0];

        $result = $this->obj->root();
        $obj = $result[0];
        $actual = $obj->toArray();
        $this->assertEquals($expect, $actual);
    }

    /**
    *
    */
    #[Test]
    public function treePath()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $where = clone $this->modelData;
        $where->cd_id = $cd_id = '560802';
        $result = $this->obj->treePath($where);

        $this->assertEquals(4, count($result));

        $dataset = require("{$this->path}treePath.php");
        $expect_dataset = $dataset['test._modeldbtree'];

        $i = 0;

        foreach ($result as $obj) {
            $actual = $obj->toArray();
            $expect = $expect_dataset[$i];
            $this->assertEquals($expect, $actual);
            $i++;
        }
    }

    /**
    *
    */
    #[Test]
    public function searchTree()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        //深さ優先ASC limit無し
        $where = clone $this->modelData;
        $where->cd_id = $cd_id = '560000';
        $result = $this->obj->searchTree($where);

        $this->assertEquals(12, count($result));

        $dataset = require("{$this->path}searchTreeDepthAsc.php");
        $expect_dataset = $dataset['test._modeldbtree'];

        $i = 0;

        foreach ($result as $obj) {
            $actual = $obj->toArray();
            $expect = $expect_dataset[$i];
            $this->assertEquals($expect, $actual);
            $i++;
        }

        //深さ優先ASC limit有り
        $result = $this->obj->searchTree($where, 'depth', 'cd_id', 2);

        $this->assertEquals(6, count($result));

        $dataset = require("{$this->path}searchTreeDepthAscLimit.php");
        $expect_dataset = $dataset['test._modeldbtree'];

        $i = 0;

        foreach ($result as $obj) {
            $actual = $obj->toArray();
            $expect = $expect_dataset[$i];
            $this->assertEquals($expect, $actual);
            $i++;
        }

        //深さ優先DESC limit有り
        $result = $this->obj->searchTree($where, 'depth', 'cd_id DESC', 3);

        $this->assertEquals(12, count($result));

        $dataset = require("{$this->path}searchTreeDepthDesc.php");
        $expect_dataset = $dataset['test._modeldbtree'];

        $i = 0;

        foreach ($result as $obj) {
            $actual = $obj->toArray();
            $expect = $expect_dataset[$i];
            $this->assertEquals($expect, $actual);
            $i++;
        }

        //幅優先ASC limit無し
        $result = $this->obj->searchTree($where, 'breadth', 'cd_id');

        $this->assertEquals(12, count($result));

        $dataset = require("{$this->path}searchTreeBreadthAsc.php");
        $expect_dataset = $dataset['test._modeldbtree'];

        $i = 0;

        foreach ($result as $obj) {
            $actual = $obj->toArray();
            $expect = $expect_dataset[$i];
            $this->assertEquals($expect, $actual);
            $i++;
        }

        //幅優先ASC limit有り
        $result = $this->obj->searchTree($where, 'breadth', 'cd_id', 2);

        $this->assertEquals(6, count($result));

        $i = 0;

        foreach ($result as $obj) {
            $actual = $obj->toArray();
            $expect = $expect_dataset[$i];
            $this->assertEquals($expect, $actual);
            $i++;
        }

        //幅優先DESC limit有り
        $result = $this->obj->searchTree($where, 'breadth', 'cd_id DESC', 2);

        $this->assertEquals(6, count($result));

        $dataset = require("{$this->path}searchTreeBreadthDesc.php");
        $expect_dataset = $dataset['test._modeldbtree'];

        $i = 0;

        foreach ($result as $obj) {
            $actual = $obj->toArray();
            $expect = $expect_dataset[$i];
            $this->assertEquals($expect, $actual);
            $i++;
        }

        //ID存在しない
        $where = clone $this->modelData;
        $where->cd_id = $cd_id = '999999';
        $result = $this->obj->searchTree($where);
        $this->assertEmpty($result);
    }

    /**
    *
    */
    #[Test]
    public function crud()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        //挿入
        $data = clone $this->modelData;
        $data->cd_id = '999';
        $data->cd_parent = '560800';
        $data->no_data = 21;
        $data->nm_data = 'Leaf1';

        $this->obj->graft($data);

        //root挿入
        $data = clone $this->modelData;
        $data->cd_id = '888';
        $data->no_data = 22;
        $data->nm_data = 'root1';

        $this->obj->graft($data);

        $dataset = require("{$this->path}crudGraft.php");

        $stmt = $this->executeQuery(
            "SELECT * FROM test._modeldbtree ORDER BY cd_id ",
            [],
            $this->pdo,
        );

        $table = (array)$stmt->fetchAll();

        $this->assertEquals($table, $dataset[$this->tablename]);

        //移動
        $target = clone $this->modelData;
        $target->cd_id = '999';
        $where = clone $this->modelData;
        $where->cd_id = '560002';

        $this->obj->move($target, $where);

        $dataset = require("{$this->path}crudMove.php");

        $stmt = $this->executeQuery(
            "SELECT * FROM test._modeldbtree ORDER BY cd_id ",
            [],
            $this->pdo,
        );

        $table = (array)$stmt->fetchAll();

        $this->assertEquals($table, $dataset[$this->tablename]);

        //枝刈り
        $target = clone $this->modelData;
        $target->cd_id = '999';

        $this->obj->prune($target);

        $dataset = require("{$this->path}crudPrune.php");

        $stmt = $this->executeQuery(
            "SELECT * FROM test._modeldbtree ORDER BY cd_id ",
            [],
            $this->pdo,
        );

        $table = (array)$stmt->fetchAll();

        $this->assertEquals($table, $dataset[$this->tablename]);

        //枝抜き
        $target = clone $this->modelData;
        $target->cd_id = '560700';

        $this->obj->pull($target);

        $dataset = require("{$this->path}crudPull.php");

        $stmt = $this->executeQuery(
            "SELECT * FROM test._modeldbtree ORDER BY cd_id ",
            [],
            $this->pdo,
        );

        $table = (array)$stmt->fetchAll();

        $this->assertEquals($table, $dataset[$this->tablename]);
    }
}
