<?php

declare(strict_types=1);

namespace test\Concerto\auth\ratelimit;

use test\Concerto\ConcertoTestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use Concerto\auth\ratelimit\{
    RateLimitterFactory,
    RateLimitterInterface,
    SqliteRateLimitterRepositoryFactory,
};
use PDO;

class RateLimitterFactoryTest extends ConcertoTestCase
{
    private $repository;

    protected function setUp(): void
    {
        $this->repository =
            SqliteRateLimitterRepositoryFactory::create(':memory:');
    }

    public static function buildProvider()
    {
        return [
            ['simple', 1, 5],
            ['DUMMY', 2, 10],
        ];
    }

    #[Test]
    #[DataProvider('buildProvider')]
    public function build(
        string $name,
        int $interval,
        int $limit,
    ) {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new RateLimitterFactory(
            $this->repository,
        );

        $actual = $obj->build(
            $name,
            $interval,
            $limit,
        );

        $this->assertInstanceOf(
            RateLimitterInterface::class,
            $actual,
        );

        $this->assertEquals(
            $interval,
            $this->getPrivateProperty(
                $actual,
                'interval',
            ),
        );

        $this->assertEquals(
            $limit,
            $this->getPrivateProperty(
                $actual,
                'limit',
            ),
        );
    }
}
