<?php

declare(strict_types=1);

namespace test\Concerto\auth\ratelimit;

use test\Concerto\ConcertoTestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use Concerto\auth\ratelimit\{
    SimpleRateLimitter,
    SqliteRateLimitterRepositoryFactory,
};

class SimpleRateLimitterTest extends ConcertoTestCase
{
    private $repository;

    protected function setUp(): void
    {
        $this->repository =
            SqliteRateLimitterRepositoryFactory::create(':memory:');
    }

    private function generateId(): string
    {
        return str_shuffle(uniqid('', true));
    }

    public static function checkLimitProvider()
    {
        return [
            [1, 5],
        ];
    }

    #[Test]
    #[DataProvider('checkLimitProvider')]
    public function checkLimit(
        int $interval,
        int $limit,
    ) {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new SimpleRateLimitter(
            $this->repository,
            $interval,
            $limit,
        );

        $id = $this->generateId();

        for ($i = $limit; $i >= 0; $i--) {
            $this->repository->save($id);

            if ($i === 0) {
                $this->assertFalse(
                    $obj->isAccepted($id),
                    "down count={$i}",
                );
            } else {
                $this->assertTrue(
                    $obj->isAccepted($id),
                    "down count={$i}",
                );
            }
        }
    }

    public static function checkIntervalProvider()
    {
        return [
            [1, 1],
            [2, 1],
        ];
    }

    #[Test]
    #[DataProvider('checkIntervalProvider')]
    public function checkInterval(
        int $interval,
        int $limit,
    ) {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new SimpleRateLimitter(
            $this->repository,
            $interval,
            $limit,
        );

        $id = $this->generateId();

        for ($i = 0; $i <= $limit; $i++) {
            $this->repository->save($id);
        }

        $this->assertFalse(
            $obj->isAccepted($id),
        );

        sleep($interval + 1);

        $this->assertTrue(
            $obj->isAccepted($id),
        );
    }
}
