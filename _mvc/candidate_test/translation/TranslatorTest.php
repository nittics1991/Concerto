<?php

declare(strict_types=1);

namespace test\Concerto\translation;

use test\Concerto\ConcertoTestCase;
use candidate\translation\Translator;

class TranslatorTest extends ConcertoTestCase
{
    /**
    *   @test
    */
    public function first()
    {
//      $this->markTestIncomplete();

        $obj = new Translator(__DIR__ . '/messages/message1.php');
        $messages = $this->getPrivateProperty($obj, 'messages');

        $actual = $messages['msg1'];
        $this->assertEquals($actual, $obj->trans('msg1'));

        $actual = $messages['msg1.child1'];
        $actual = mb_ereg_replace('%s', '配列', $actual);
        $this->assertEquals($actual, $obj->trans('msg1.child1', ['配列']));

        //not defined id
        $this->assertEquals('', $obj->trans('DUMMY', [1, 2, 3]));
    }

    /**
    *   @test
    */
    public function readMessageFile()
    {
//      $this->markTestIncomplete();

        $obj = new Translator();
        $obj->readMessageFile(__DIR__ . '/messages/message1.php');
        $obj->readMessageFile(__DIR__ . '/messages/message2.php');

        $messages = $this->getPrivateProperty($obj, 'messages');
        $actual = $messages['msg2'];
        $this->assertEquals($actual, $obj->trans('msg2'));
    }

    /**
    *   @test
    */
    public function faildPathName()
    {
//      $this->markTestIncomplete();

        $this->expectException(\InvalidArgumentException::class);
        $obj = new Translator(__DIR__ . '/messages/DUMMY.php');
    }

    /**
    *   @test
    */
    public function faildMsgParameter()
    {
        $this->markTestIncomplete();

        $this->expectException(\ErrorException::class);
        $obj = new Translator(__DIR__ . '/messages/message1.php');

        //mst be parameter
        echo $obj->trans('msg1.child1');
    }
}
