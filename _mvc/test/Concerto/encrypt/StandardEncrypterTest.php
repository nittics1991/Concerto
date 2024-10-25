<?php

declare(strict_types=1);

namespace test\Concerto\encrypt;

use test\Concerto\ConcertoTestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use Concerto\encrypt\StandardEncrypter;

class StandardEncrypterTest extends ConcertoTestCase
{
    /**
    */
    #[Test]
    public function simpleSuccess()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $loop_count = 10;

        for ($i = 0; $i < $loop_count; $i++) {
            $key = random_bytes(32);
            $text = random_bytes(32);

            $encripter = new StandardEncrypter($key);

            $this->assertEquals($key, $encripter->getKey());

            $encoded = $encripter->encrypt($text);
            $decorded = $encripter->decrypt($encoded);

            $this->assertTrue($text === $decorded);
        }
    }
}
