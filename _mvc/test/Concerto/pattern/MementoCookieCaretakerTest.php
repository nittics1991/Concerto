<?php

declare(strict_types=1);

namespace test\Concerto\pattern;

use test\Concerto\ConcertoTestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use Concerto\pattern\MementoCookieCaretaker;
use Concerto\standard\Cookie;

class MementoCookieCaretakerTestCookie extends Cookie
{
    private array $storages = [];

    public function __set(
        string $name,
        mixed $value
    ): void {
        $this->storages[$name] = $value;
    }

    public function __get(
        string $name
    ): mixed {
        return $this->__isset($name) ?
            $this->storages[$name] : null;
    }

    public function __isset(
        string $name
    ): bool {
        return array_key_exists(
            $name,
            $this->storages,
        );
    }
}


////////////////////////////////////////////////////////////

class MementoCookieCaretakerTest extends ConcertoTestCase
{
    /**
    *
    */
    #[Test]
    public function allMethod()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $cookie = new MementoCookieCaretakerTestCookie();

        $object = new MementoCookieCaretaker(
            $cookie,
        );

        $data = new \ArrayObject([1, 2, 3]);
        $object->setStorage('prop1', $data);

        $expect = serialize($data);
        $storage = $this->getPrivateProperty($object, 'cookie');
        $this->assertEquals($expect, $storage->prop1);

        $this->assertEquals($data, $object->getStorage('prop1'));
        $this->assertEquals(null, $object->getStorage('DUMMY'));
    }
}
