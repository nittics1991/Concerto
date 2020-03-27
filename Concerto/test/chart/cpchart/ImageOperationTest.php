<?php

declare(strict_types=1);

namespace Concerto\test\chart\cpchart;

use Concerto\test\ConcertoTestCase;
use Concerto\chart\cpchart\ImageOperation;

class ImageOperationTest extends ConcertoTestCase
{
    /**
    *   @test
    */
    public function construct1Exception()
    {
//      $this->markTestIncomplete();
        
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('resource type not matche');
        $image = 1;
        $obj = new ImageOperation($image);
    }
    
    /**
    *   @test
    **/
    public function construct1()
    {
//      $this->markTestIncomplete();
        
        $image = imagecreatetruecolor(10, 20);
        $obj = new ImageOperation($image);
        $actual = $this->getPrivateProperty($obj, 'image');
        $this->assertEquals('gd', get_resource_type($actual));
    }
    
    /**
    *   @test
    **/
    public function createFromFile()
    {
//      $this->markTestIncomplete();
        
        $obj = ImageOperation::createFromFile(
            __DIR__ . '\\tmp\\afterCanvas1.png'
        );
        $actual = $this->getPrivateProperty($obj, 'image');
        $this->assertEquals('gd', get_resource_type($actual));
    }
    
    /**
    *   @test
    **/
    public function merge1()
    {
//      $this->markTestIncomplete();
        
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
