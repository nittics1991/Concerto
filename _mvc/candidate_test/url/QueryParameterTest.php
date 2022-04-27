<?php

declare(strict_types=1);

namespace test\Concerto\url;

use test\Concerto\ConcertoTestCase;
use candidate\url\QueryParameter;

class QueryParameterTest extends ConcertoTestCase
{
    /**
    *   @test
    */
    public function basicOperation()
    {
//      $this->markTestIncomplete();

        $querys = [
            'key1' => 'a',
            'key2' => 'b',
            'key3' => 'c',
        ];

        $obj = new QueryParameter($querys);

        $this->assertEquals('b', $obj->get('key2', 'default'));
        $this->assertEquals('default', $obj->get('key0', 'default'));
        $this->assertEquals(true, $obj->has('key2'));
        $this->assertEquals(true, $obj->has('key3'));
        $this->assertEquals(false, $obj->has('key0'));

        $obj->set('key4', 'ccc');
        $this->assertEquals('ccc', $obj->get('key4'));
        $obj->unset('key3');
        $this->assertEquals(false, $obj->has('key3'));

        $expect = [
            'key1' => 'a',
            'key2' => 'b',
            'key4' => 'ccc',
        ];
        $this->assertEquals($expect, $obj->all());

        $expect = 'key1=a&key2=b&key4=ccc';
        $this->assertEquals($expect, $obj->__toString());
        $this->assertEquals($expect, $obj->buildQuery());
    }

    /**
    *   @test
    */
    public function fromString()
    {
//      $this->markTestIncomplete();

        $expect = 'key1=a&key2=b&key4=ccc';
        $obj = QueryParameter::fromString($expect);
        $this->assertEquals($expect, $obj->buildQuery());
    }
}
