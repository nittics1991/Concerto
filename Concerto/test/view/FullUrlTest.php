<?php

declare(strict_types=1);

namespace Concerto\test\url;

use Concerto\test\ConcertoTestCase;
use Concerto\view\FullUrl;

class FullUrlTest extends ConcertoTestCase
{
    /**
    *   @test
    **/
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
