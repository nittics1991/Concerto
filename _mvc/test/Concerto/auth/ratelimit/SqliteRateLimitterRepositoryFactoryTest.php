<?php

declare(strict_types=1);

namespace test\Concerto\auth\ratelimit;

use test\Concerto\ConcertoTestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use Concerto\auth\ratelimit\{
    RateLimitterRepository,
    SqliteRateLimitterRepositoryFactory
};
use PDO;

class SqliteRateLimitterRepositoryFactoryTest extends ConcertoTestCase
{
    protected function setUp(): void
    {
    }

    #[Test]
    public function hasCacheTable()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new SqliteRateLimitterRepositoryFactory();

        $pdo = new PDO('sqlite::memory:');

        $this->assertFalse(
            $this->callPrivateMethod(
                $obj,
                'hasCacheTable',
                [$pdo],
            ),
        );

        $sql = "
            CREATE TABLE rate_limit (
                id TEXT
                , create_at INTEGER
            );
        ";

        $pdo->exec($sql);

        $this->assertTrue(
            $this->callPrivateMethod(
                $obj,
                'hasCacheTable',
                [$pdo],
            ),
        );
    }


    #[Test]
    public function createCacheTable()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new SqliteRateLimitterRepositoryFactory();

        $pdo = new PDO('sqlite::memory:');

        $this->callPrivateMethod(
            $obj,
            'createCacheTable',
            [$pdo],
        );

        $sql = "PRAGMA table_list('rate_limit')";

        $stmt = $pdo->prepare($sql);

        $stmt->execute();

        $actualList = $stmt->fetchAll();

         $this->assertTrue(
             count($actualList) === 1,
         );

        $sql = "PRAGMA table_xinfo('rate_limit')";

        $stmt = $pdo->prepare($sql);

        $stmt->execute();

        $actualList = $stmt->fetchAll();

         $this->assertTrue(
             count($actualList) === 2,
         );

         $this->assertTrue(
             mb_ereg_match('id', $actualList[0]['name']),
         );

         $this->assertTrue(
             mb_ereg_match('create_at', $actualList[1]['name']),
         );
    }

    #[Test]
    public function createPDO()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new SqliteRateLimitterRepositoryFactory();

        $pdo = $this->callPrivateMethod(
            $obj,
            'createPDO',
            ['sqlite::memory:'],
        );

        $sql = "PRAGMA table_list('rate_limit')";

        $stmt = $pdo->prepare($sql);

        $stmt->execute();

        $actualList = $stmt->fetchAll();

         $this->assertTrue(
             count($actualList) === 1,
         );

        $sql = "PRAGMA table_xinfo('rate_limit')";

        $stmt = $pdo->prepare($sql);

        $stmt->execute();

        $actualList = $stmt->fetchAll();

         $this->assertTrue(
             count($actualList) === 2,
         );

         $this->assertTrue(
             mb_ereg_match('id', $actualList[0]['name']),
         );

         $this->assertTrue(
             mb_ereg_match('create_at', $actualList[1]['name']),
         );
    }


    #[Test]
    public function createRateLimitter()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $actual = SqliteRateLimitterRepositoryFactory::create();

        $this->assertInstanceOf(
            RateLimitterRepository::class,
            $actual,
        );
    }
}
