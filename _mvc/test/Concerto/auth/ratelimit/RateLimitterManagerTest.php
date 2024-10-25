<?php

declare(strict_types=1);

namespace test\Concerto\auth\ratelimit;

use test\Concerto\ConcertoTestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use Concerto\auth\ratelimit\{
    RateLimitterFactory,
    RateLimitterInterface,
    RateLimitterManager,
    RateLimitterRepositoryInterface,
    SqliteRateLimitterRepositoryFactory,
};

class RateLimitterManagerTest extends ConcertoTestCase
{
    private $repository;
    private $limitterFactory;

    protected function setUp(): void
    {
        $this->repository =
            SqliteRateLimitterRepositoryFactory::create(':memory:');

        $this->limitterFactory = new RateLimitterFactory(
            $this->repository,
        );
    }

    private function setId(
        ?bool $useProxyServerIp = false,
    ): string {
        $id = str_shuffle(uniqid('', true));

        if ($useProxyServerIp) {
            $_SERVER['x-forwarded-for'] = $id;
        } else {
            $_SERVER['remote_addr'] = $id;
        }

        return $id;
    }

    public static function constructProvider()
    {
        return [
            [null, null, 60 * 60 * 2, 5],
            [10, 20, 10, 20],
            [-10, -20, -10, 20],
            [10, 101, 10, 100],
        ];
    }

    #[Test]
    #[DataProvider('constructProvider')]
    public function construct(
        ?int $expiration,
        ?int $garbagePer,
        int $expectExpiration,
        int $expectgarbagePer,
    ) {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->setId();

        $obj = new RateLimitterManager(
            $this->repository,
            $this->limitterFactory,
            $expiration,
            $garbagePer,
        );

        $this->assertEquals(
            $expectExpiration,
            $this->getPrivateProperty(
                $obj,
                'expiration',
            ),
        );

        $this->assertEquals(
            $expectgarbagePer,
            $this->getPrivateProperty(
                $obj,
                'garbagePer',
            ),
        );
    }

    #[Test]
    public function getId()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $expect = mb_ereg_replace(
            '\.',
            '_',
            $this->setId(),
        );

        $obj = new RateLimitterManager(
            $this->repository,
            $this->limitterFactory,
        );

        $this->assertEquals(
            $expect,
            $this->callPrivateMethod(
                $obj,
                'getId',
                [],
            ),
        );

        $expect = mb_ereg_replace(
            '\.',
            '_',
            $this->setId(true),
        );

        $obj = new RateLimitterManager(
            $this->repository,
            $this->limitterFactory,
        );

        $this->assertEquals(
            $expect,
            $this->callPrivateMethod(
                $obj,
                'getId',
                [],
            ),
        );
    }

    #[Test]
    public function generateRandumNo()
    {
        $this->setId();

        $obj = new RateLimitterManager(
            $this->repository,
            $this->limitterFactory,
        );

        for ($i = 0; $i < 100; $i++) {
            $randums[] = $this->callPrivateMethod(
                $obj,
                'generateRandumNo',
                [],
            );
        }

        $this->assertEquals(
            [],
            array_filter(
                $randums,
                fn($val) => $val < 0 || $val > 100,
            ),
        );
    }

    public static function garbageHitProvider()
    {
        return [
            [0, 1, false],
            [1, 1, true],
            [10, 11, false],
            [100, 1, true],
            [100, 100, true],
            [-10, 10, true],
            [-10, 11, false],
            [-100, 100, true],
        ];
    }

    #[Test]
    #[DataProvider('garbageHitProvider')]
    public function garbageHit(
        int $garbagePer,
        int $randumNo,
        bool $expect
    ) {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->setId();

        $mockRepository = new class () implements RateLimitterRepositoryInterface
        {
            public $isCalled = false;

            public function save(
                string $id,
            ) {
            }

            public function fetch(
                string $id,
                int $interval,
            ): array {
                return [];
            }

                //garbage‚ªhit‚µ‚½‚Æ‚«call‚³‚ê‚é
            public function delete(
                int $interval,
            ) {
                $this->isCalled = true;
            }
        };

        $limitterFactory = $this->limitterFactory;
        $expiration = 99;

        $double = new class (
            $mockRepository,
            $limitterFactory,
            $expiration,
            $garbagePer,
            $randumNo,
        ) extends RateLimitterManager
        {
            protected $randumNo;

            public function __construct(
                $repository,
                $limitterFactory,
                $expiration,
                $garbagePer,
                $randumNo,
            ) {
                parent::__construct(
                    $repository,
                    $limitterFactory,
                    $expiration,
                    $garbagePer,
                );

                $this->randumNo = $randumNo;
            }

            protected function generateRandumNo(): int
            {
                return $this->randumNo;
            }
        };

        $this->callPrivateMethod(
            $double,
            'garbage',
            [],
        );

        $this->assertEquals(
            $expect,
            $mockRepository->isCalled,
        );
    }

    #[Test]
    public function record()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->setId();

        $mockRepository = new class () implements RateLimitterRepositoryInterface
        {
            public $id;

            public function save(
                string $id,
            ) {
                $this->id = $id;
            }

            public function fetch(
                string $id,
                int $interval,
            ): array {
                return [];
            }

            public function delete(
                int $interval,
            ) {
            }
        };

        $obj = new RateLimitterManager(
            $mockRepository,
            $this->limitterFactory,
        );

        $obj->record();

        $this->assertEquals(
            $this->getPrivateProperty(
                $obj,
                'id',
            ),
            $mockRepository->id,
        );
    }

    public static function policyProvider()
    {
        return [
            [11,21,null],
            [12,22,'simple'],
            [13,23,'DUMMY'],
        ];
    }

    #[Test]
    #[DataProvider('policyProvider')]
    public function policy(
        int $interval,
        int $limit,
        ?string $name,
    ) {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->setId();

        $obj = new RateLimitterManager(
            $this->repository,
            $this->limitterFactory,
        );

        $obj->policy(
            $interval,
            $limit,
            $name,
        );

        $limitter = $this->getPrivateProperty(
            $obj,
            'limitter',
        );

        $this->assertInstanceOf(
            RateLimitterInterface::class,
            $limitter,
        );

        $this->assertEquals(
            $interval,
            $this->getPrivateProperty(
                $limitter,
                'interval',
            ),
        );

        $this->assertEquals(
            $limit,
            $this->getPrivateProperty(
                $limitter,
                'limit',
            ),
        );
    }

    public static function isAcceptedProvider()
    {
        return [
            [1, 1, 0, 0, 0, true],
            [1, 1, 1, 0, 0, true],
            [1, 1, 2, 0, 0, false],
            [1, 1, 2, 1, 0, true],
            [1, 1, 2, 0, 1, false],
        ];
    }

    #[Test]
    #[DataProvider('isAcceptedProvider')]
    public function isAccepted(
        int $interval,
        int $limit,
        int $recordCount,
        int $recordSleep,
        int $afterSleep,
        bool $expect,
    ) {
//     $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->setId();

        $manager = new RateLimitterManager(
            $this->repository,
            $this->limitterFactory,
        );

        $obj = $manager->policy($interval, $limit);

        for ($i = 0; $i < $recordCount; $i++) {
            $obj->record();
            sleep($recordSleep);
        }

        sleep($afterSleep);

        $actual = $obj->isAccepted();

        $this->assertEquals(
            $expect,
            $actual,
        );
    }

    public static function multiLimitterProvider()
    {
        return [
            [5, 2, 2, true, 4, 3, false],
        ];
    }

    #[Test]
    #[DataProvider('multiLimitterProvider')]
    public function multiLimitter(
        int $recordCount,
        int $interval1,
        int $limit1,
        bool $expect1,
        int $interval2,
        int $limit2,
        bool $expect2,
    ) {
//     $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->setId();

        $manager = new RateLimitterManager(
            $this->repository,
            $this->limitterFactory,
        );

        $obj1 = $manager->policy($interval1, $limit1);

        $obj2 = $manager->policy($interval2, $limit2);

        for ($i = 0; $i < $recordCount; $i++) {
            $manager->record();
            sleep(1);
        }

        $this->assertEquals(
            $expect1,
            $obj1->isAccepted(),
        );

        $this->assertEquals(
            $expect2,
            $obj2->isAccepted(),
        );
    }

    public static function counterProvider()
    {
        return [
            [0, 0],
            [5, 5],
        ];
    }

    #[Test]
    #[DataProvider('counterProvider')]
    public function counter(
        int $recordCount,
        int $expect,
    ) {
//     $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->setId();

        $obj = new RateLimitterManager(
            $this->repository,
            $this->limitterFactory,
        );

        for ($i = 0; $i < $recordCount; $i++) {
            $obj->record();
        }

        $this->assertEquals(
            $expect,
            $obj->count(),
        );
    }
}
