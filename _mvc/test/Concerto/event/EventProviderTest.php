<?php

declare(strict_types=1);

namespace test\Concerto\event;

use test\Concerto\ConcertoTestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use Concerto\event\EventObject;
use Concerto\event\EventProvider;

class EventProviderTest extends ConcertoTestCase
{
    public static function addListener1Provider()
    {
        return [
            [
                'name1',
                fn($o) => $o,
                0,
            ],
            [
                self::class,
                new class {
                    public function __invoke(...$values)
                    {
                        return $values;
                    }
                },
                PHP_INT_MIN,
            ],
        ];
    }

    #[Test]
    #[DataProvider('addListener1Provider')]
    public function addListener1(
        string $id,
        callable $listener,
        int $priority,
    ) {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new EventProvider();

        $obj->addListener(
            $id,
            $listener,
            $priority,
        );

        $actuals = $this->getPrivateProperty(
            $obj,
            'listeners',
        );

        $this->assertEquals(
            $listener,
            $actuals[$id][$priority][0],
        );
    }

    public static function addListener2Provider()
    {
        return [
            [
                [
                    [
                        'name1',
                        fn($o) => $o,
                        0,
                    ],
                    [
                        self::class,
                        new class {
                            public function __invoke(...$values)
                            {
                                return $values;
                            }
                        },
                        PHP_INT_MAX,
                    ],
                    [
                        'name1',
                        fn($o) => [$o],
                        0,
                    ],
                    [
                        self::class,
                        new class {
                            public function __invoke(...$values)
                            {
                                return array_reverse($values);
                            }
                        },
                        PHP_INT_MAX,
                    ],
                ],
            ],
        ];
    }

    #[Test]
    #[DataProvider('addListener2Provider')]
    public function addListener2(
        array $configs,
    ) {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new EventProvider();

        foreach ($configs as [$id, $listener, $priority]) {
            $obj->addListener(
                $id,
                $listener,
                $priority,
            );
        }

        $actuals = $this->getPrivateProperty(
            $obj,
            'listeners',
        );

        $remember = [];

        foreach ($configs as [$id, $listener, $priority]) {
            if (isset($remember[$id][$priority])) {
                $i = $remember[$id][$priority] ;
            } else {
                $i = $remember[$id][$priority] = 0;
            }

            $this->assertEquals(
                $listener,
                $actuals[$id][$priority][$i],
                "loop no={$i} id={$id} priority={$priority}"
            );

            ++$remember[$id][$priority];
        }
    }

    public function listenersTestData()
    {
        return [
            'name1' => [
                0 => [
                    fn($o) => "name1-0-1",
                    fn($o) => "name1-0-2",
                ],
            ],
            self::class => [
                PHP_INT_MAX => [
                    new class {
                        public function __invoke(...$values)
                        {
                            return self::class .
                                '-' .
                                (string)PHP_INT_MAX .
                                '-' .
                                '1';
                        }
                    },
                    new class {
                        public function __invoke(...$values)
                        {
                            return self::class .
                                '-' .
                                (string)PHP_INT_MAX .
                                '-' .
                                '2';
                        }
                    },
                ],
                PHP_INT_MIN => [
                    new class {
                        public function __invoke(...$values)
                        {
                            return self::class .
                                '-' .
                                (string)PHP_INT_MIN .
                                '-' .
                                '1';
                        }
                    },
                ],
                0 => [
                    new class {
                        public function __invoke(...$values)
                        {
                            return self::class .
                                '-' .
                                (string)0 .
                                '-' .
                                '1';
                        }
                    },
                ],
            ],
        ];
    }

    #[Test]
    public function getEvents()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new EventProvider();

        $dataset = $this->listenersTestData();

        $this->setPrivateProperty(
            $obj,
            'listeners',
            $dataset,
        );

        $iterator = $this->callPrivateMethod(
            $obj,
            'getEvents',
            ['name1'],
        );

        $expects = $dataset['name1'][0];

        foreach ($iterator as $i => $listener) {
            $this->assertEquals(
                $expects[$i],
                $listener,
                "name1 loop no={$i}"
            );
        }

        $iterator = $this->callPrivateMethod(
            $obj,
            'getEvents',
            [self::class],
        );

        $expects = [];

        $expects[] = $dataset[self::class][PHP_INT_MIN][0];
        $expects[] = $dataset[self::class][0][0];
        $expects[] = $dataset[self::class][PHP_INT_MAX][0];
        $expects[] = $dataset[self::class][PHP_INT_MAX][1];

        $i = 0;

        foreach ($iterator as $listener) {
            $this->assertEquals(
                $expects[$i],
                $listener,
                self::class . " loop no={$i}" . PHP_EOL .
                    $expects[$i]() . PHP_EOL .
                    $listener() . PHP_EOL,
            );

            $i++;
        }
    }

    public function getListenersForEventTestData1()
    {
        return [
            //index=0
            [
                'name1',
                fn($o) => "name1-0-1",
                0,
            ],
            //index=1
            [
                self::class,
                new class {
                    public function __invoke(...$values)
                    {
                        return self::class .
                            '-' .
                            (string)PHP_INT_MAX .
                            '-' .
                            '1';
                    }
                },
                PHP_INT_MAX,
            ],
            //index=2
            [
                'name1',
                fn($o) => "name1-0-2",
                0,
            ],
            //index=3
            [
                'name1',
                fn($o) => "name1-0-3",
                -5,
            ],
            //index=4
            [
                self::class,
                new class {
                    public function __invoke(...$values)
                    {
                        return self::class .
                            '-' .
                            (string)PHP_INT_MIN .
                            '-' .
                            '2';
                    }
                },
                PHP_INT_MIN,
            ],
            //index=5
            [
                self::class,
                new class {
                    public function __invoke(...$values)
                    {
                        return self::class .
                            '-' .
                            (string)PHP_INT_MAX .
                            '-' .
                            '3';
                    }
                },
                PHP_INT_MAX,
            ],
            //index=6
            [
                self::class,
                new class {
                    public function __invoke(...$values)
                    {
                        return self::class .
                            '-' .
                            (string)PHP_INT_MAX .
                            '-' .
                            '4';
                    }
                },
                PHP_INT_MAX,
            ],
        ];
    }

    #[Test]
    public function getListenersForEvent1()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new EventProvider();

        $dataset = $this->getListenersForEventTestData1();

        foreach ($dataset as $data) {
            $obj->addListener(
                $data[0],
                $data[1],
                $data[2],
            );
        }

        $event = new self('');

        $iterator = $obj->getListenersForEvent(
            $event,
        );

        $actuals = iterator_to_array($iterator);

        $expects = [
            $dataset[4][1],
            $dataset[1][1],
            $dataset[5][1],
            $dataset[6][1],
        ];

        $i = 0;

        foreach ($expects as $callable) {
            $this->assertEquals(
                $expects[$i],
                $callable,
                "loop no={$i}",
            );

            $i++;
        }
    }

    public function namedEvent()
    {
        return [
            //index=0
            [
                'name1',
                fn($o) => "name1-0-1",
                0,
            ],
            //index=1
            [
                'name2',
                fn($o) => "name2-0-1",
                0,
            ],
            //index=2
            [
                'name1',
                fn($o) => "name1-1-1",
                0,
            ],
        ];
    }

    #[Test]
    public function namedEvent1()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new EventProvider();

        $dataset = $this->namedEvent();

        foreach ($dataset as $data) {
            $obj->addListener(
                $data[0],
                $data[1],
                $data[2],
            );
        }

        $event = new EventObject(
            'name1'
        );

        $iterator = $obj->getListenersForEvent(
            $event,
        );

        $actuals = iterator_to_array($iterator);

        $expects = [
            $dataset[0][1],
            $dataset[2][1],
        ];

        $i = 0;

        foreach ($expects as $callable) {
            $this->assertEquals(
                $expects[$i],
                $callable,
                "loop no={$i}",
            );

            $i++;
        }
    }
}
