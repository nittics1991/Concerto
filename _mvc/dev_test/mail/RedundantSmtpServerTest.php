<?php

declare(strict_types=1);

namespace test\Concerto\mail;

use test\Concerto\ConcertoTestCase;
use dev\mail\{
    MailMessage,
    MailTransferInterface,
    RedundantSmtpServer
};

class SmtpSuccess implements MailTransferInterface
{
    public function send(MailMessage $messages):bool
    {
        return true;
    }
}

class SmtpFailure implements MailTransferInterface
{
    public function send(MailMessage $messages):bool
    {
        return false;
    }
}

//////////////////////////////////////////////////////////////////////////////////

class RedundantSmtpServerTest extends ConcertoTestCase
{

    /**
    *   add
    *
    *   @test
    */
    public function add()
    {
//      $this->markTestIncomplete();

        $object = new RedundantSmtpServer();
        $expect = [];
        $expect[0] = new SmtpSuccess();
        $object->add($expect[0]);
        $this->assertEquals($expect, $this->getPrivateProperty($object, 'servers'));

        $expect[1] = new SmtpSuccess();
        $object->add($expect[1]);
        $this->assertEquals($expect, $this->getPrivateProperty($object, 'servers'));

        $expect[2] = new SmtpSuccess();
        $object->add($expect[2]);
        $this->assertEquals($expect, $this->getPrivateProperty($object, 'servers'));

        //constructor setting
        $object = new RedundantSmtpServer($expect);
        $this->assertEquals($expect, $this->getPrivateProperty($object, 'servers'));
    }

    public function sendExceptionProvider()
    {
        return [
            [
               'DUMMY',
            ],
            [
               12,
            ],
        ];
    }

    /**
    *   sendException
    *
    *   @test
    *   @dataProvider sendExceptionProvider
    */
    public function sendException($data)
    {
//      $this->markTestIncomplete();

        $object = new RedundantSmtpServer([]);
        $object->add(new SmtpSuccess());
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("required MailMessage");
        $object->send($data);
    }

    public function sendProvider()
    {
        $message = new MailMessage([
            'from' => ['from@toshiba.co.jp' => 'FROM'],
            'to' => ['to@toshiba.co.jp' => 'TO'],
        ]);

        return [
            [
                [new SmtpSuccess(), new SmtpSuccess()],
                $message,
                null
            ],
            [
                [new SmtpFailure(), new SmtpFailure(), new SmtpSuccess()],
                $message,
                null
            ],
        ];
    }

    /**
    *   send
    *
    *   @test
    *   @dataProvider sendProvider
    */
    public function send($servers, $mails, $expect)
    {
//      $this->markTestIncomplete();

        $object = new RedundantSmtpServer($servers);
        $this->assertEquals($expect, $object->send($mails));
    }

    public function sendFailureProvider()
    {
        $message = new MailMessage([
            'from' => ['from@toshiba.co.jp' => 'FROM'],
            'to' => ['to@toshiba.co.jp' => 'TO'],
        ]);

        return [
            [
                [new SmtpFailure(), new SmtpFailure()],
                $message,
                $message
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider sendFailureProvider
    */
    public function sendFailure($servers, $mails, $expect)
    {
//      $this->markTestIncomplete();

        $object = new RedundantSmtpServer($servers);
        
        try {
            $object->send($mails);
        } catch(\RuntimeException $e) {
            $this->assertEquals(1,1);
        }
        $this->assertEquals(1,0);
    }

    public function RedundantProvider()
    {
        $message = new MailMessage([
            'from' => ['from@toshiba.co.jp' => 'FROM'],
            'to' => ['to.yata@glb.toshiba.co.jp' => 'TO'],
        ]);
        
        return [
            [
                [new SmtpSuccess(), new SmtpSuccess()],
                $message,
                true
            ],
            [
                [new SmtpFailure(), new SmtpSuccess()],
                $message,
                true
            ],
        ];
    }

    /**
    *   Redundant(prophecy mock)
    *
    *   @test
    *   @dataProvider RedundantProvider
    */
    public function Redundant($servers, $mails, $expect)
    {
//      $this->markTestIncomplete();

        $stubs = [];
        for (
            $i = 0, $length = count($servers);
            $i < $length;
            $i++
        ) {
            $prop = $this->prophesize(get_class($servers[$i]));
            $prop->willImplement('Concerto\mail\MailTransferInterface');

            $prop->send($mails)->shouldBeCalledTimes(1);
            $stubs[$i] = $prop->reveal();
        }

        $object = new RedundantSmtpServer($stubs);
        $result = $object->send($mails);
    }
}
