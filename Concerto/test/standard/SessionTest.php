<?php

declare(strict_types=1);

namespace Concerto\test\standard;

use Concerto\test\ConcertoTestCase;
use Concerto\standard\Session;

class SessionTest extends ConcertoTestCase
{
    public function setUp(): void
    {
        //@session_start();
        parent::setUp();
    }
    
    //test functionを複数作ると「Cannot send session cookie - headers already sent」
    //とエラーになる
    
    /**
    *   @runInSeparateProcess
    */
    public function testAll()
    {
//      $this->markTestIncomplete();
        
        $obj = new Session('test');
        $this->assertTrue($obj->isNull());
        
        session_start();
        if (!isset($_SESSION['test'])) {
            $_SESSION['test']['prop1']  = 'str';
            $_SESSION['test']['prop2']  = 'numner';
            $_SESSION['test']['prop3']  = array('a', 'b', 'c' => array('D', 'E'), 'f');
            $_SESSION['test']['prop4']  = 'bool';
        }
        $this->assertFalse($obj->isNull());
        $this->assertTrue(isset($obj->prop2));
        
        unset($obj->prop2);
        $this->assertFalse(isset($obj->prop2));
        
        $obj->unsetAll();
        $this->assertTrue($obj->isNull());
        
        $expect = array('str', 'numner', array('a', 'b', 'c' => array('D', 'E'), 'f'));
        $obj->fromArray($expect);
        $this->assertEquals($expect, $obj->toArray());
        
        $id = session_id();
        $obj->changeID();
        $this->assertNotEquals($id, session_id());
        
        $i = 0;
        foreach ($obj as $key => $val) {
            $this->assertEquals($i, $key);
            $i++;
        }
    }
}
