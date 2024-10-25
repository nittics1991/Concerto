<?php

declare(strict_types=1);

namespace test\Concerto\hashing;

use test\Concerto\ConcertoTestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use Concerto\hashing\OpenSslRandomNumberGenarator;

class OpenSslRandomNumberGenaratorTest extends ConcertoTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
    */
    #[Test]
    public function main()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new OpenSslRandomNumberGenarator();
        $hash = $obj->generate();
        $this->assertEquals(16 * 2, mb_strlen($hash));

        $obj = new OpenSslRandomNumberGenarator(20);
        $hash = $obj->generate();
        $this->assertEquals(20 * 2, mb_strlen($hash));
    }
}
