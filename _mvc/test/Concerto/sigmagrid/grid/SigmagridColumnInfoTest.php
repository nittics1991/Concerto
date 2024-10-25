<?php

declare(strict_types=1);

namespace test\Concerto\sigmagrid;

use test\Concerto\ConcertoTestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use Concerto\sigmagrid\grid\SigmagridColumnInfo;

class SigmagridColumnInfoTest extends ConcertoTestCase
{
    /**
    */
    #[Test]
    public function construct1()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $params = [
            'id' => 'cd_tanto',
            'header' => '担当名',
            'fieldName' => 'tanto',
            'fieldIndex' => 3,
            'sortOrder' => 'asc',
            'hidden' => true,
            'exportable' => false,
            'printable' => true,
        ];

        $object = new SigmagridColumnInfo($params);
        $this->assertEquals($params, $object->toArray());
        $this->assertEquals(true, $object->isValid());
    }
}
