<?php

declare(strict_types=1);

namespace Concerto\test\pattern;

use Concerto\test\ConcertoTestCase;
use Concerto\pattern\MementoCookieCaretaker;

class MementoCookieCaretakerTest extends ConcertoTestCase
{
    public function setUp(): void
    {
    }

    /**
    *   @test
    *
    */
    public function allMethod()
    {
//      $this->markTestIncomplete();

        $cookie = new \StdClass();
        $object = new MementoCookieCaretaker($cookie);

        $data = new \ArrayObject([1, 2, 3]);
        $object->setStorage('prop1', $data);

        $expect = serialize($data);
        $storage = $this->getPrivateProperty($object, 'cookie');
        $this->assertEquals($expect, $storage->prop1);

        $this->assertEquals($data, $object->getStorage('prop1'));
        $this->assertEquals(null, $object->getStorage('DUMMY'));
    }
}
