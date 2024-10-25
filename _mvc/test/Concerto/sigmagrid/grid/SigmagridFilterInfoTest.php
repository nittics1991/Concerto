<?php

declare(strict_types=1);

namespace test\Concerto\sigmagrid;

use test\Concerto\ConcertoTestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use Concerto\sigmagrid\grid\SigmagridFilterInfo;

class SigmagridFilterInfoTest extends ConcertoTestCase
{
    /**
    */
    #[Test]
    public function construct1()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $params = [
            'fieldName' => 'cd_tanto',
            'value' => 12,
            'logic' => 'lessEqual',
        ];

        $object = new SigmagridFilterInfo($params);
        $this->assertEquals($params, $object->toArray());
        $this->assertEquals(true, $object->isValid());
    }
}
