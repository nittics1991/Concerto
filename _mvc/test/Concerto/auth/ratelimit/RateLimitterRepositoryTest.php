<?php

declare(strict_types=1);

namespace test\Concerto\auth\ratelimit;

use test\Concerto\ConcertoTestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use Concerto\auth\ratelimit\RateLimitterRepository;
use Exception;
use PDO;

class RateLimitterRepositoryTest extends ConcertoTestCase
{
    private $pdo;
    private $tableName = 'test_cache';
    private $idColumnName = 'id';
    private $timestampColumnName = 'create_time';

    protected function setUp(): void
    {
    }

    private function createObject()
    {
        $this->pdo = new PDO(
            'sqlite::memory:',
            null,
            null,
            [PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC],
        );

        $sql = "
            CREATE TABLE {$this->tableName} (
                {$this->idColumnName} TEXT,
                {$this->timestampColumnName} INT
            );
        ";

        $this->pdo->exec($sql);

        $sql = "
            PRAGMA table_info({$this->tableName})
        ";

        $stmt = $this->pdo->query($sql);

        if (count($stmt->fetchAll()) === 0) {
            throw new Exception('Failed to create table');
        }

        return new RateLimitterRepository(
            $this->pdo,
            $this->tableName,
            $this->idColumnName,
            $this->timestampColumnName,
        );
    }

    private function generateId(): string
    {
        return str_shuffle(uniqid('', true));
    }

    private function getAllTableData(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM {$this->tableName}");
        return $stmt->fetchAll();
    }

    #[Test]
    public function basic1()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = $this->createObject();

        $id = $this->generateId();

        //•Û‘¶
        $obj->save($id);

        $fetched = $obj->fetch(
            $id,
            1,
        );

        $this->assertTrue(count($fetched) === 1);

        sleep(2);

        $fetched = $obj->fetch(
            $id,
            1,
        );

        $this->assertTrue(count($fetched) === 0);

        //•Û‘¶
        $obj->save($id);

        $fetched = $obj->fetch(
            $id,
            1,
        );

        $this->assertTrue(count($fetched) === 1);

        //íœ
        $obj->delete(1);

        $allItems = $this->getAllTableData();
        $this->assertTrue(count($allItems) === 1);

        $fetched = $obj->fetch(
            $id,
            1,
        );

        $this->assertTrue(count($fetched) === 1);
    }

    #[Test]
    public function basic2()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = $this->createObject();

        $id1 = $this->generateId();
        $id2 = $this->generateId();

        //•Û‘¶1
        $obj->save($id1);

        //•Û‘¶2
        $obj->save($id2);

        $fetched = $obj->fetch(
            $id1,
            1,
        );

        $this->assertTrue(count($fetched) === 1);

        sleep(2);

        //•Û‘¶1
        $obj->save($id1);

        $fetched = $obj->fetch(
            $id1,
            1,
        );

        $this->assertTrue(count($fetched) === 1);

        $fetched = $obj->fetch(
            $id1,
            2,
        );

        $this->assertTrue(count($fetched) === 2);

        $fetched = $obj->fetch(
            $id2,
            2,
        );

        $this->assertTrue(count($fetched) === 1);

        sleep(1);

        $obj->delete(2);

        $fetched = $obj->fetch(
            $id1,
            4,
        );

        $this->assertTrue(count($fetched) === 1);
    }

    #[Test]
    public function emptyFetch()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = $this->createObject();

        $id = $this->generateId();

        $fetched = $obj->fetch(
            $id,
            1,
        );

        $this->assertTrue(count($fetched) === 0);
    }

    #[Test]
    public function allCleanup()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = $this->createObject();

        $id = $this->generateId();

        $obj->save($id);
        $obj->save($id);
        $obj->save($id);
        $obj->save($id);
        $obj->save($id);

        $fetched = $obj->fetch(
            $id,
            1,
        );

        $this->assertTrue(count($fetched) === 5);

        $obj->delete();

        $fetched = $obj->fetch(
            $id,
            2,
        );

        $this->assertTrue(count($fetched) === 0);
    }
}
