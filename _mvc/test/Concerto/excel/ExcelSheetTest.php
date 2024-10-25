<?php

declare(strict_types=1);

namespace test\Concerto\excel;

use test\Concerto\ConcertoTestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use Concerto\excel\ExcelSheet;
use DateTimeImmutable;
use DateTimeInterface;
use stdClass;

class ExcelSheetTest extends ConcertoTestCase
{
    #[Test]
    public function getSheetName()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $sheet_name = 'テストシート';

        $obj = new ExcelSheet($sheet_name);

        $this->assertEquals(
            $sheet_name,
            $obj->getSheetName(),
        );
    }

    public static function validateDataProvider()
    {
        $date_src = [
            '2024-05-09',
            '2024-02-28',
            '2024-12-31',
        ];

        $datetimes = array_map(
            function ($d) {
                return (new DateTimeImmutable($d))
                    ->format(DateTimeInterface::ATOM);
            },
            $date_src,
        );

        return [
            [
                [
                    1 => [1,2,3,4,5],
                    12 => [11.1,22.2,33.3,44.4,55.5],
                    8 => ['abc','def','ghi'],
                    3 => $datetimes,
                ],
            ],
        ];
    }

    #[Test]
    #[DataProvider('validateDataProvider')]
    public function validateData(
        array $data,
    ) {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $sheet_name = 'テストシート';

        $obj = new ExcelSheet($sheet_name);

        $this->setPrivateProperty(
            $obj,
            'mapping_data',
            $data,
        );

        $this->assertEquals(1, 1);
    }

    #[Test]
    #[DataProvider('validateDataProvider')]
    public function setMappingData(
        array $data,
    ) {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $sheet_name = 'テストシート';

        $obj = new ExcelSheet($sheet_name);

        $obj->setMappingData($data);

        $actual = $this->getPrivateProperty(
            $obj,
            'mapping_data',
        );

        $this->assertEquals(
            $data,
            $actual,
        );
    }

    public static function toIndexedProvider()
    {
        return [
            [
                [
                    12 => [
                        3 => 1,
                        7 => 2,
                        11 => 3,
                    ],
                    22 => [
                        13 => 11,
                        27 => 12,
                        111 => 13,
                    ],
                ],
                [
                    [1, 2, 3],
                    [11, 12, 13],
                ],
            ],
        ];
    }

    #[Test]
    #[DataProvider('toIndexedProvider')]
    public function toIndexed(
        array $data,
        array $expect,
    ) {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $sheet_name = 'テストシート';

        $obj = new ExcelSheet($sheet_name);

        $actual = $this->callPrivateMethod(
            $obj,
            'toIndexed',
            [$data],
        );

        $this->assertEquals(
            $expect,
            $actual,
        );
    }

    public static function addDataProvider()
    {
        $address[0] = 'C10';
        $address[1] = $address[0];

        $data[0] = [
            12 => [
                3 => 1,
                7 => 2,
                11 => 3,
            ],
            22 => [
                13 => 11,
                27 => 12,
                111 => 13,
            ],
        ];
        $data[1] = $data[0];

        $obj[0] = new stdClass();
        ($obj[0])->address = $address[0];
        ($obj[0])->data = [
            0 => [1, 2, 3],
            1 => [11, 12, 13],
        ];

        $obj[1] = new stdClass();
        ($obj[1])->address = $address[0];
        ($obj[1])->data = $data[1];

        $expect[0] = $obj[0];
        $expect[1] = $obj[1];

        return [
            [
                $address[0],
                $data[0],
                true,
                [$expect[0]],
            ],
            [
                $address[1],
                $data[1],
                false,
                [$expect[1]],
            ],
        ];
    }

    #[Test]
    #[DataProvider('addDataProvider')]
    public function addData(
        string $address,
        array $data,
        bool $toIndexed,
        array $expect,
    ) {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $sheet_name = 'テストシート';

        $obj = new ExcelSheet($sheet_name);

        $obj->addData($address, $data, $toIndexed);

        $actual = $this->getPrivateProperty(
            $obj,
            'values',
        );

        $this->assertEquals(
            $expect,
            $actual,
        );
    }

    public static function mappingDataProvider()
    {
        $container[0] = new stdClass();
        ($container[0])->address = 'C10';
        ($container[0])->data = [
            0 => [1, 2, 3],
            1 => [11, 12, 13],
        ];

        $expect[0] = [
            10 => [
                3 => 1,
                4 => 2,
                5 => 3,
            ],
            11 => [
                3 => 11,
                4 => 12,
                5 => 13,
            ],
        ];

        $container[1] = new stdClass();
        ($container[1])->address = 'H25';
        ($container[1])->data = [
            [
                1 => 12,
                2 => '青木',
                3 => new DateTimeImmutable('2024-1-3'),
            ],
            [
                1 => 38,
                2 => '伊藤',
                3 => new DateTimeImmutable('2021-11-13'),
            ],
        ];

        $expect[1] = [
            25 => [
                9 => 12,
                10 => '青木',
                11 => new DateTimeImmutable('2024-1-3'),
            ],
            26 => [
                9 => 38,
                10 => '伊藤',
                11 => new DateTimeImmutable('2021-11-13'),
            ],
        ];

        return [
            [$container[0], $expect[0]],
            [$container[1], $expect[1]],
        ];
    }

    #[Test]
    #[DataProvider('mappingDataProvider')]
    public function mappingData(
        stdClass $container,
        array $expect,
    ) {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $sheet_name = 'テストシート';

        $obj = new ExcelSheet($sheet_name);

        $this->callPrivateMethod(
            $obj,
            'mappingData',
            [$container],
        );

        $actual = $this->getPrivateProperty(
            $obj,
            'mapping_data',
        );

        $this->assertEquals(
            $expect,
            $actual,
        );
    }

    public static function sortMappingDataProvider()
    {
        $data[0] = [
            11 => [
                3 => 11,
                5 => 13,
                4 => 12,
            ],
            10 => [
                5 => 3,
                3 => 1,
                4 => 2,
            ],
        ];

        $expect[0] = [
            10 => [
                3 => 1,
                4 => 2,
                5 => 3,
            ],
            11 => [
                3 => 11,
                4 => 12,
                5 => 13,
            ],
        ];
        return [
            [$data[0], $expect[0]],
        ];
    }

    #[Test]
    #[DataProvider('sortMappingDataProvider')]
    public function sortMappingData(
        array $data,
        array $expect,
    ) {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $sheet_name = 'テストシート';

        $obj = new ExcelSheet($sheet_name);

        $obj->setMappingData($data);

        $this->callPrivateMethod(
            $obj,
            'sortMappingData',
            [],
        );

        $actual = $this->getPrivateProperty(
            $obj,
            'mapping_data',
        );

        $this->assertEquals(
            $expect,
            $actual,
        );
    }

    public static function toArrayProvider()
    {
        $address[0] = 'C10';

        $data[0] = [
            [
                12,
                '青木',
                new DateTimeImmutable('2024-1-3'),
            ],
            [
                'id' => 38,
                'name' => '伊藤',
                'createAt' => new DateTimeImmutable('2021-11-13'),
            ],
        ];

        $toIndexed[0] = false;

        $expect[0] = [
            10 => [
                3 => 12,
                4 => '青木',
                5 => new DateTimeImmutable('2024-1-3'),
            ],
            11 => [
                3 => 38,
                4 => '伊藤',
                5 => new DateTimeImmutable('2021-11-13'),
            ],
        ];

        $address[1] = 'H23';

        $data[1] = [
            [
                'id' => 12,
                'name' => '青木',
                'createAt' => new DateTimeImmutable('2024-1-3'),
            ],
            [
                'id' => 38,
                'name' => '伊藤',
                'createAt' => new DateTimeImmutable('2021-11-13'),
            ],
        ];

        $toIndexed[1] = true;

        $expect[1] = [
            23 => [
                8 => 12,
                9 => '青木',
                10 => new DateTimeImmutable('2024-1-3'),
            ],
            24 => [
                8 => 38,
                9 => '伊藤',
                10 => new DateTimeImmutable('2021-11-13'),
            ],
        ];

        return [
            [
                $address[0],
                $data[0],
                $toIndexed[0],
                $expect[0],
            ],
            [
                $address[1],
                $data[1],
                $toIndexed[1],
                $expect[1],
            ],
        ];
    }

    #[Test]
    #[DataProvider('toArrayProvider')]
    public function toArray(
        string $address,
        array $data,
        bool $toIndexed,
        array $expect,
    ) {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $sheet_name = 'テストシート';

        $obj = new ExcelSheet($sheet_name);

        $obj->addData($address, $data, true);

        $actual = $obj->toArray();

        $this->assertEquals(
            $expect,
            $actual,
        );
    }

    #[Test]
    public function toArray2()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $sheet_name = 'テストシート';

        $obj = new ExcelSheet($sheet_name);

        $provider_data = self::toArrayProvider();

        foreach ($provider_data as $dt) {
            $obj->addData(
                $dt[0],
                $dt[1],
                true,
            );
        }

        $actual = $obj->toArray();

        $expect = [];

        foreach ($provider_data as $dt) {
            $expect += $dt[3];
        }

        $this->assertEquals(
            $expect,
            $actual,
        );
    }
}
