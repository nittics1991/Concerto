<?php

declare(strict_types=1);

namespace Concerto\test\standard;

use Concerto\test\ConcertoTestCase;
use StdClass;
use Concerto\standard\ControllerModel;

class ControllerModel1 extends ControllerModel
{
}

class ControllerModelTest extends ConcertoTestCase
{
    private $class;

    protected function setUp(): void
    {
    //*   @runInSeparateProcessがあると動かない(phpunit bug)の影響


        //sessionはcliで動かない
        // $this->class = new ControllerModel1(new StdClass());
    }


    /**
    *   アクセッサ
    *
    *   @test
    */
    public function accessor()
    {
    //*   @runInSeparateProcessがあると動かない(phpunit bug)
        $this->markTestIncomplete();

        // $this->assertEquals(true, $this->class->isNull('prop1'));
        $this->assertEquals(true, $this->class->isNull());
        $this->assertEquals(true, $this->class->isEmpty('prop1'));
        $this->assertEquals(true, $this->class->isEmpty());

        //set&get
        $expect = 'prop';
        $this->class->prop1 = $expect;
        $this->assertEquals($expect, $this->class->prop1);

        //fromArray&toArary
        $data = array(
            'int' => 1,
            'int2' => 0,
            'str' => 'string'
        );

        $expect = array_merge($data, array('prop1' => $expect));

        $this->class->fromArray($expect);
        $this->assertEquals($expect, $this->class->toArray());

        //array access
        $expect = 'PROP2';
        $this->class['prop2'] = $expect;
        $this->assertEquals($expect, $this->class['prop2']);

        //isset
        $this->assertEquals(true, isset($this->class['prop2']));
        $this->assertEquals(false, isset($this->class['BAT']));
        $this->assertEquals(true, isset($this->class->prop2));
        $this->assertEquals(false, isset($this->class->BAT));

        //isNull

        //var_dump($this->class->prop2);echo "<hr>\r\n";
        //var_dump($this->class->isNull('prop2'));echo "<hr>\r\n";


        $this->assertEquals(false, $this->class->isNull('prop2'));
        $this->assertEquals(true, $this->class->isNull('BAT'));

        //isEmpty
        $this->assertEquals(true, $this->class->isEmpty('int2'));
        $this->assertEquals(true, $this->class->isEmpty('BAT'));

        //unset
        unset($this->class->int);
        $this->assertEquals(true, $this->class->isNull('int'));
        $this->assertEquals(false, $this->class->isNull('int2'));
    }
}
