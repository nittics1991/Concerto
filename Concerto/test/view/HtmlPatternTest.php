<?php

declare(strict_types=1);

namespace Concerto\test\url;

use Concerto\test\ConcertoTestCase;
use Concerto\view\HtmlPattern;

class HtmlPatternTest extends ConcertoTestCase
{
    /**
    *   @test
    */
    public function build()
    {
//      $this->markTestIncomplete();

        $pattern = [
            'int' => '^(+|-){0,1}\d+$',
            'date' => '^20\d{2}-\d{2}-\d{2}$',
        ];

        $obj = new HtmlPattern($pattern);
        $this->assertEquals($pattern['date'], $obj('date'));
        $this->assertEquals('', $obj('dummy'));
    }
}
