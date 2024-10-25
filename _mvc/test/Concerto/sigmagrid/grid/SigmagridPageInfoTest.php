<?php

declare(strict_types=1);

namespace test\Concerto\sigmagrid;

use test\Concerto\ConcertoTestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use Concerto\sigmagrid\grid\SigmagridPageInfo;

class SigmagridPageInfoTest extends ConcertoTestCase
{
    /**
    */
    #[Test]
    public function construct1()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $params = [
            'pageSize' => 11,
            'pageNum' => 3,
            'totalRowNum' => 100,
            'totalPageNum' => 10,
            'startRowNum' => 11,
            'endRowNum' => 20,
        ];

        $object = new SigmagridPageInfo($params);
        $this->assertEquals($params, $object->toArray());
        $this->assertEquals(true, $object->isValid());
    }
}
