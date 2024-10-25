<?php

declare(strict_types=1);

namespace test\Concerto\arrays;

use test\Concerto\ConcertoTestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use Concerto\arrays\ArrayDot;

class ArrayDotTest extends ConcertoTestCase
{
    public $object;
    public static $data = [
            'a' => 'A',
            'b' => [
                'ba' => [
                    'ca' => 'CA',
                    'cb' => [
                        'da' => 'DA'
                    ]
                ],
                'bb' => 'BB'
            ]
        ];

    public function setUp(): void
    {
    }

    public static function setProvider()
    {
        $base = self::$data;

        return [
            [$base, 'x', 'value',
                [

            'a' => 'A',
            'b' => [
                'ba' => [
                    'ca' => 'CA',
                    'cb' => [
                        'da' => 'DA'
                    ]
                ],
                'bb' => 'BB'
            ],
            'x' => 'value'

                ]
            ],  //

            [$base, 'b.x', 'value',
                [

            'a' => 'A',
            'b' => [
                'ba' => [
                    'ca' => 'CA',
                    'cb' => [
                        'da' => 'DA'
                    ]
                ],
                'bb' => 'BB',
                'x' => 'value'
            ],

                ]
            ],  //

            [$base, 'b.ba.cb.x', 'value',
                [

            'a' => 'A',
            'b' => [
                'ba' => [
                    'ca' => 'CA',
                    'cb' => [
                        'da' => 'DA',
                        'x' => 'value'
                    ]
                ],
                'bb' => 'BB'
            ],

                ]
            ],  //

            [$base, 'b.ba.ca', 'value',
                [

            'a' => 'A',
            'b' => [
                'ba' => [
                    'ca' => 'value',
                    'cb' => [
                        'da' => 'DA',
                    ]
                ],
                'bb' => 'BB'
            ],

                ]
            ],  //

            [$base, 'b.ba.cb', 'value',
                [

            'a' => 'A',
            'b' => [
                'ba' => [
                    'ca' => 'CA',
                    'cb' => 'value'
                ],
                'bb' => 'BB'
            ],

                ]
            ],  //

            [$base, 'b.ba', 'value',
                [

            'a' => 'A',
            'b' => [
                'ba' => 'value',
                'bb' => 'BB'
            ],

                ]
            ],  //
            //空データに空キーでnull設定
            [
                [],
                '',
                null,
                ['' => null]
            ], //
            //空データに一次元キーでnull設定
            [
                [],
                'aaa',
                null,
                ['aaa' => null]
            ], //
            //空データに3次元キーでnull設定
            [
                [],
                'aaa.bbb.ccc',
                null,
                [
                    'aaa' => [
                        'bbb' => [
                            'ccc' => null
                        ]
                    ]
                ]
            ], //
        ];
    }

    /**
    */
    #[Test]
    #[DataProvider('setProvider')]
    public function set1($data, $dot, $val, $expect)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertEquals($expect, ArrayDot::set($data, $dot, $val));
    }

    public static function getProvider()
    {
        $base = self::$data;

        return [
            [$base, 'a', 'A'],
            [$base, 'b.bb', 'BB'],
            [$base, 'b.ba.cb.da', 'DA'],
            [$base, 'b.bc', null],
        ];
    }

    /**
    */
    #[Test]
    #[DataProvider('getProvider')]
    public function get1($data, $dot, $expect)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertEquals($expect, ArrayDot::get($data, $dot));
    }

    public static function hasProvider()
    {
        $base = self::$data;

        return [
          [$base, 'a', true],
          [$base, 'b.ba', true],
          [$base, 'b.ba.cb.da', true],
          [$base, 'b.ba.cb.db', false],
        ];
    }

    /**
    */
    #[Test]
    #[DataProvider('hasProvider')]
    public function has1($data, $dot, $expect)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertEquals($expect, ArrayDot::has($data, $dot));
    }

    public static function removeProvider()
    {
        return [
            [
                self::$data,
                'a',
                [
                    // 'a' => 'A',
                    'b' => [
                        'ba' => [
                            'ca' => 'CA',
                            'cb' => [
                                'da' => 'DA'
                            ]
                        ],
                        'bb' => 'BB'
                    ]
                ]
            ],
            [
                self::$data,
                'b.bb',
                [
                    'a' => 'A',
                    'b' => [
                        'ba' => [
                            'ca' => 'CA',
                            'cb' => [
                                'da' => 'DA'
                            ]
                        ],
                        // 'bb' => 'BB'
                    ]
                ]
            ],
            [
                self::$data,
                'b.ba.cb',
                [
                    'a' => 'A',
                    'b' => [
                        'ba' => [
                            'ca' => 'CA',
                            // 'cb' => [
                                // 'da' => 'DA'
                            // ]
                        ],
                        'bb' => 'BB'
                    ]
                ]
            ],
            [
                self::$data,
                'b.z',
                [
                    'a' => 'A',
                    'b' => [
                        'ba' => [
                            'ca' => 'CA',
                            'cb' => [
                                'da' => 'DA'
                            ]
                        ],
                        'bb' => 'BB'
                    ]
                ]
            ],
            [
                self::$data,
                'z',
                [
                    'a' => 'A',
                    'b' => [
                        'ba' => [
                            'ca' => 'CA',
                            'cb' => [
                                'da' => 'DA'
                            ]
                        ],
                        'bb' => 'BB'
                    ]
                ]
            ],
            //空データ空キー
            [
                [],
                '',
                [],
            ],
            //空データ未存在キー
            [
                [],
                'X',
                [],
            ],
        ];
    }

    /**
    */
    #[Test]
    #[DataProvider('removeProvider')]
    public function remove1($data, $dot, $expect)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertEquals($expect, ArrayDot::remove($data, $dot));
    }
}
