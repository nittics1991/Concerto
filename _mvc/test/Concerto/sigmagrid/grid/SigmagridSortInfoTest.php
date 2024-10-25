<?php

declare(strict_types=1);

namespace test\Concerto\sigmagrid;

use test\Concerto\ConcertoTestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use Concerto\sigmagrid\grid\SigmagridSortInfo;

class SigmagridSortInfoTest extends ConcertoTestCase
{
    /**
    */
    #[Test]
    public function construct1()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $params = [
            'columnId' => 'cd_tanto',
            'fieldName' => 'tanto',
            'sortOrder' => 'asc',
            'getSortValue' => 'abc',
            'sortFn' => 'krsort',
        ];

        $object = new SigmagridSortInfo($params);
        $this->assertEquals($params, $object->toArray());
        $this->assertEquals(true, $object->isValid());
    }
}
