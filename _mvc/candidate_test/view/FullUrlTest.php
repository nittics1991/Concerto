<?php

declare(strict_types=1);

namespace test\Concerto\url;

use test\Concerto\ConcertoTestCase;
use candidate\view\FullUrl;

class FullUrlTest extends ConcertoTestCase
{
    /**
    *   @test
    */
    public function build()
    {
//      $this->markTestIncomplete();

        $baseUrl = 'http://example.co.jp/path/to/real/';
        $obj = new FullUrl($baseUrl);

        $url = '../test/tmp/target';
        $actual = 'http://example.co.jp/path/to/test/tmp/target';
        $this->assertEquals($actual, $obj($url));
    }
}
