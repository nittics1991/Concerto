<?php

declare(strict_types=1);

namespace test\Concerto\mbstring;

use test\Concerto\ConcertoTestCase;
use Concerto\mbstring\ZlibStringCompresser;

class ZlibStringCompresserTest extends ConcertoTestCase
{
    public function compressAndExpandProvider()
    {
        return [
            [
                $data[0][] = bin2hex(random_bytes(16)),
                $data[0][] = ZlibStringCompresser::GZIP,
                $data[0][] = 3,
                call_user_func_array(
                    'zlib_encode',
                    $data[0],
                ),
            ],
            [
                $data[1][] = bin2hex(random_bytes(32)),
                $data[1][] = ZlibStringCompresser::DEFLATE,
                $data[1][] = -1,
                call_user_func_array(
                    'zlib_encode',
                    $data[1],
                ),
            ],
            [
                $data[2][] = bin2hex(random_bytes(128)),
                $data[2][] = ZlibStringCompresser::RAW,
                $data[2][] = -1,
                call_user_func_array(
                    'zlib_encode',
                    $data[2],
                ),
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider compressAndExpandProvider
    */
    public function compressAndExpand(
        string $string,
        int $encoding,
        int $level,
        string $compressed,
    ) {
//      $this->markTestIncomplete("--- markTestIncomplete ---");

        $obj = new ZlibStringCompresser(
            $encoding,
            $level,
        );

        $this->assertEquals(
            $compressed,
            $obj->compress($string),
        );

        $this->assertEquals(
            $string,
            $obj->expand($compressed),
        );
    }

    public function isCompressedProvider()
    {
        return [
            [
                zlib_encode(
                    bin2hex(random_bytes(1024)),
                    ZlibStringCompresser::GZIP,
                    3,
                ),
                true,
            ],
            [
                bin2hex(random_bytes(1024)),
                false,
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider isCompressedProvider
    */
    public function isCompressed(
        string $string,
        bool $expect,
    ) {
//      $this->markTestIncomplete("--- markTestIncomplete ---");

        $obj = new ZlibStringCompresser();

        $this->assertEquals(
            $expect,
            $obj->isCompressed($string),
        );
    }
}
