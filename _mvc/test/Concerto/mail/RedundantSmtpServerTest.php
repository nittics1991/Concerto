<?php

declare(strict_types=1);

namespace test\Concerto\mail;

use test\Concerto\ConcertoTestCase;
use Concerto\mail\MailMessage;
use Concerto\mail\MailTransferInterface;
use Concerto\mail\RedundantSmtpServer;

class SmtpSuccess implements MailTransferInterface
{
    public function send($param)
    {
        return true;
    }
}

class SmtpFailure implements MailTransferInterface
{
    public function send($param)
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
//      $this->markTestIncomplete('--- markTestIncomplete ---');

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
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $object = new RedundantSmtpServer([]);
        $object->add(new SmtpSuccess());
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("required MailMessage");
        $object->send($data);
    }

    public function sendProvider()
    {
        $message = new MailMessage();

        return [
            [
                [new SmtpSuccess(), new SmtpSuccess()],
                new MailMessage(),
                null
            ],
            [
                [new SmtpFailure(), new SmtpFailure()],
                $message,
                $message
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
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $object = new RedundantSmtpServer($servers);
        $this->assertEquals($expect, $object->send($mails));
    }

    public function RedundantProvider()
    {
        return [
            [
                [new SmtpSuccess(), new SmtpSuccess()],
                new MailMessage(),
                [1, 0]
            ],
            [
                [new SmtpFailure(), new SmtpSuccess()],
                new MailMessage(),
                [0, 1]
            ],
            [
                [new SmtpSuccess(), new SmtpSuccess()],
                new MailMessage(),
                [1, 0]
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
//      $this->markTestIncomplete('--- markTestIncomplete ---');

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

    /**
    *   Redundant(phpunit mock)
    *
    *   @test
    *   @dataProvider RedundantProvider
    */
    public function Redundant2($servers, $mails, $expect)
    {
        $this->markTestIncomplete(
            '--- this test is created "phpunit mock". ' .
            ' same test on Redundant test. ---'
        );

        //dataProvider 1件目はOK
        $stubs = [];
        for (
            $i = 0, $length = count($servers);
            $i < $length;
            $i++
        ) {
            $stubs[$i] = $this
                ->getMockBuilder(get_class($servers[$i]))
                ->setMethods(['send'])
                ->getMock()
            ;

            $stubs[$i]
                ->expects($this->exactly($expect[$i]))
                ->method('send')
                ->will($this->returnValue(true))
            ;
        }

        $object = new RedundantSmtpServer($stubs);
        $result = $object->send($mails);
    }
}
