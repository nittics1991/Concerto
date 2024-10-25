<?php

declare(strict_types=1);

namespace test\Concerto\excel;

use test\Concerto\ConcertoTestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use Concerto\excel\ExcelAddress;

class ExcelAddressTest extends ConcertoTestCase
{
    public static function extractRowAddressProvider()
    {
        return [
            ['1', 1],
            ['10', 10],
            ['A10', 10],
            ['B99', 99],
            ['B$99', 99],
            ['$CC$777', 777],
        ];
    }

    #[Test]
    #[DataProvider('extractRowAddressProvider')]
    public function extractRowAddress(
        string $address,
        int $expect,
    ) {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertEquals(
            $expect,
            $this->callPrivateMethod(
                ExcelAddress::class,
                'extractRowAddress',
                [$address],
                true,
            ),
        );
    }

    public static function extractColumnAddressProvider()
    {
        return [
            ['A', 1],
            ['b', 2],
            ['Z', 26],
            ['AA', 27],
            ['AB', 28],
            ['AZ', 52],
            ['BA', 53],
            ['BB', 54],
            ['BZ', 78],
            ['CA', 79],
            ['ZZ', 702],
            ['AAA', 703],
            ['AAB', 704],
            ['AZZ', 1378],
            ['BAA', 1379],
            ['XFD', 16384],
            ['xfd', 16384],
            ['A3', 1],
            ['B3', 2],
            ['$Z3', 26],
            ['$xfd3', 16384],
            ['$xfd$777', 16384],
        ];
    }

    #[Test]
    #[DataProvider('extractColumnAddressProvider')]
    public function extractColumnAddress(
        string $address,
        int $expect,
    ) {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertEquals(
            $expect,
            $this->callPrivateMethod(
                ExcelAddress::class,
                'extractColumnAddress',
                [$address],
                true,
            ),
        );
    }

    public static function addressToLocationProvider()
    {
        return [
            [
                'B5',
                [5,2],
            ],
            [
                'AA12345',
                [12345,27],
            ],
            [
                '$AA12345',
                [12345,27],
            ],
            [
                'AA$12345',
                [12345,27],
            ],
            [
                '$AA$12345',
                [12345,27],
            ],
            [
                'B5:Z9',
                [5,2,9,26],
            ],
            [
                '$AA99:BA126',
                [99,27,126,53],
            ],
            [
                '$AA99:BA$126',
                [99,27,126,53],
            ],
            [
                '$AA$99:$BA$126',
                [99,27,126,53],
            ],
        ];
    }

    #[Test]
    #[DataProvider('addressToLocationProvider')]
    public function addressToLocation(
        string $address,
        array $expect,
    ) {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertEquals(
            $expect,
            ExcelAddress::addressToLocation($address),
        );
    }

    public static function columnNoToAddressProvider()
    {
        return [
            [1,'A'],
            [2,'B'],
            [26,'Z'],
            [27,'AA'],
            [28,'AB'],
            [52,'AZ'],
            [53,'BA'],
            [54,'BB'],
            [78,'BZ'],
            [79,'CA'],
            [702,'ZZ'],
            [703,'AAA'],
            [704,'AAB'],
            [1378,'AZZ'],
            [1379,'BAA'],
            [16384,'XFD'],
        ];
    }

    #[Test]
    #[DataProvider('columnNoToAddressProvider')]
    public function columnNoToAddress(
        int $column_no,
        string $expect,
    ) {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertEquals(
            $expect,
            $this->callPrivateMethod(
                ExcelAddress::class,
                'columnNoToAddress',
                [$column_no],
                true,
            ),
        );
    }

    public static function locationToAddressProvider()
    {
        return [
            [
                [5,2],
                'B5',
            ],
            [
                [12345,27],
                'AA12345',
            ],
            [
                [5,2,9,26],
                'B5:Z9',
            ],
            [
                [99,27,126,53],
                'AA99:BA126',
            ],
            [
                [99,1379,126,16384],
                'BAA99:XFD126',
            ],
        ];
    }

    #[Test]
    #[DataProvider('locationToAddressProvider')]
    public function locationToAddress(
        array $location,
        string $expect,
    ) {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertEquals(
            $expect,
            ExcelAddress::locationToAddress($location),
        );
    }
}
