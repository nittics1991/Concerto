<?php

declare(strict_types=1);

namespace Concerto\test\pattern;

use Concerto\test\ConcertoTestCase;
use Concerto\pattern\ReverseIterator;
use ArrayObject;

class ReverseIteratorTest extends ConcertoTestCase
{
    public function setUp(): void
    {
    }

    /**
    *   @test
    *
    */
    public function iterator()
    {
//      $this->markTestIncomplete();

        $expect = array(1, 2, 3, 4, 5);
        $obj = new ReverseIterator($expect);
        $i = 4;

        foreach ($obj as $val) {
            $this->assertEquals($expect[$i], $val);
            $i--;
        }
    }

    /**
    *   @test
    *
    */
    public function iterator2()
    {
//      $this->markTestIncomplete();

        $expect = array(1, 2, 3, 4, 5);
        $obj = new ReverseIterator(new ArrayObject($expect));
        $i = 4;

        foreach ($obj as $val) {
            $this->assertEquals($expect[$i], $val);
            $i--;
        }
    }
}
