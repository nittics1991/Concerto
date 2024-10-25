<?php

declare(strict_types=1);

namespace test\Concerto\event;

use test\Concerto\ConcertoTestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use Concerto\event\NamedEventTrait;

////////////////////////////////////////////////////////////
class NamedEventTraitTestClass1
{
    use NamedEventTrait;
}

////////////////////////////////////////////////////////////

class NamedEventTraitTest extends ConcertoTestCase
{
    public static function mainProvider()
    {
        return [
            [
                'test.name',
                self::class,
            ],
        ];
    }

    #[Test]
    #[DataProvider('mainProvider')]
    public function main(
        string $eventName,
    ) {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new NamedEventTraitTestClass1();

        $obj->setEventName($eventName);

        $this->assertEquals(
            $eventName,
            $obj->getEventName(),
        );
    }
}
