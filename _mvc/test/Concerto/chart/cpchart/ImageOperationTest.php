<?php

declare(strict_types=1);

namespace test\Concerto\chart\cpchart;

use test\Concerto\ConcertoTestCase;
use Concerto\chart\cpchart\ImageOperation;

class ImageOperationTest extends ConcertoTestCase
{
    /**
    *   @test
    */
    public function construct1()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $image = imagecreatetruecolor(10, 20);
        $obj = new ImageOperation($image);
        $actual = $this->getPrivateProperty($obj, 'image');
        $this->assertEquals($actual instanceof \GdImage, true);
    }

    /**
    *   @test
    */
    public function createFromFile()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = ImageOperation::createFromFile(
            __DIR__ . '\\tmp\\afterCanvas1.png'
        );
        $actual = $this->getPrivateProperty($obj, 'image');
        $this->assertEquals($actual instanceof \GdImage, true);
    }

    /**
    *   @test
    */
    public function merge1()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $image = imagecreatetruecolor(200, 200);
        imagefilledrectangle(
            $image,
            10,
            10,
            190,
            190,
            imagecolorallocate($image, 255, 0, 0)
        );
        $obj = new ImageOperation($image);

        $file = __DIR__ . '\\tmp\\afterCanvas1.png';

        $x = $obj->merge($file, 20, 20);
            $x->output(__DIR__ . '\\tmp\\merge1.png');
        $this->assertEquals(1, 1);
    }
}
