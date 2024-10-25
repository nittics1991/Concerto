<?php

declare(strict_types=1);

namespace test\Concerto\event;

use test\Concerto\ConcertoTestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use Concerto\event\EventNameBuilder;

////////////////////////////////////////////////////////////
class EventNameBuilderTestClass1
{
    public function method1(): void
    {
    }

    public function method2(): void
    {
    }
}

////////////////////////////////////////////////////////////

class EventNameBuilderTest extends ConcertoTestCase
{
    public static function build1Provider()
    {
        return [
            [
                EventNameBuilderTestClass1::class,
                'method1',
                'before',
                'test\Concerto\event\EventNameBuilderTestClass1' .
                    '::method1' .
                    '.before',
            ],
        ];
    }

    #[Test]
    #[DataProvider('build1Provider')]
    public function build1(
        string $className,
        string $methodName,
        string $suffix,
        string $expect
    ) {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertEquals(
            $expect,
            EventNameBuilder::build(
                $className,
                $methodName,
                $suffix,
            ),
        );
    }
}
