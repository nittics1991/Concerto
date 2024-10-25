<?php

declare(strict_types=1);

namespace test\Concerto\hashing;

use test\Concerto\ConcertoTestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use Concerto\hashing\StandardHasher;

class StandardHasherTest extends ConcertoTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    public static function mainProvider()
    {
        return [
            [
                'password',
                []
            ],
            [
                'vj@03 vン:あwfkv-^2居r-「hgbmv」vlg[q3',
                ['cost ' => 20]
            ],
        ];
    }

    /**
    */
    #[Test]
    #[DataProvider('mainProvider')]
    public function main($password, $options)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new StandardHasher($options);

        $hash = $obj->hash($password);
        $this->assertEquals(true, $obj->check($hash));
        $this->assertEquals(true, $obj->verify($password, $hash));
    }
}
