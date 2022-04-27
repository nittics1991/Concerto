<?php

declare(strict_types=1);

namespace test\Concerto\arrays;

use test\Concerto\ConcertoTestCase;
use Concerto\arrays\ArrayDot;

class ArrayDotTest extends ConcertoTestCase
{
    public $object;
    public $data = [
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

    public function setProvider()
    {
        $base = $this->data;

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

        ];
    }

    /**
    *   @test
    *   @dataProvider setProvider
    */
    public function set1($data, $dot, $val, $expect)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertEquals($expect, ArrayDot::set($data, $dot, $val));
    }

    public function getProvider()
    {
        $base = $this->data;

        return [
            [$base, 'a', 'A'],
            [$base, 'b.bb', 'BB'],
            [$base, 'b.ba.cb.da', 'DA'],
            [$base, 'b.bc', null],
        ];
    }

    /**
    *   @test
    *   @dataProvider getProvider
    */
    public function get1($data, $dot, $expect)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertEquals($expect, ArrayDot::get($data, $dot));
    }

    public function hasProvider()
    {
        $base = $this->data;

        return [
          [$base, 'a', true],
          [$base, 'b.ba', true],
          [$base, 'b.ba.cb.da', true],
          [$base, 'b.ba.cb.db', false],
        ];
    }

    /**
    *   @test
    *   @dataProvider hasProvider
    */
    public function has1($data, $dot, $expect)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertEquals($expect, ArrayDot::has($data, $dot));
    }

    public function removeProvider()
    {
        return [
            [
                $this->data,
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
                $this->data,
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
                $this->data,
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
                $this->data,
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
                $this->data,
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
        ];
    }

    /**
    *   @test
    *   @dataProvider removeProvider
    */
    public function remove1($data, $dot, $expect)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertEquals($expect, ArrayDot::remove($data, $dot));
    }
}
