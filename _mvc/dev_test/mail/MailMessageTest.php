<?php

declare(strict_types=1);

namespace test\Concerto\mail;

use test\Concerto\ConcertoTestCase;
use dev\mail\MailMessage;

class MailMessageTest extends ConcertoTestCase
{

    public function constructProvidor()
    {
        $params1 = [
            'from' => ['aaa@localhost' => 'AAA'],
            'to' => ['bbb@localhost' => 'BBB', 'b@localhost' => 'B'],
            'cc' => ['ccc@localhost' => 'CCC'],
            'bcc' => ['ddd@localhost' => 'DDD', 'd@localhost' => 'D'],
            'subject' => 'メールタイトル',
            'message' => '本文\r\n漢字も使う\r改行記号を変えた',
            'attach' => [
                [
                    'file' => __DIR__ . '\\MailMessageTest.php',
                    'mime' => 'plane/text'
                ],
                [
                    'file' => __DIR__ . '\\MailSwiftSmtpTest.php'
                ]
            ],
            'type' => 'text'
        ];

        return [
            [$params1]
        ];
    }

    /**
    *   construct
    *
    *   @test
    *   @dataProvider constructProvidor
    */
    public function construct($data)
    {
//      $this->markTestIncomplete();

        $object = new MailMessage($data);
        $this->assertEquals($data, $object->toArray());
    }

    /**
    *   accessor
    *
    *   @test
    *   @dataProvider constructProvidor
    */
    public function accessor($data)
    {
//      $this->markTestIncomplete();

        $object = new MailMessage($data);
        $this->assertEquals($data['from'], $object->from);
        $this->assertEquals($data['to'], $object->to);
        $this->assertEquals($data['cc'], $object->cc);
        $this->assertEquals($data['bcc'], $object->bcc);
        $this->assertEquals($data['subject'], $object->subject);
        $this->assertEquals($data['message'], $object->message);
        $this->assertEquals($data['attach'], $object->attach);
        $this->assertEquals($data['type'], $object->type);
    }

    public function isValidMailAddressProvidor()
    {
        return [
            ['DUMMY', false],
            [[], true],
            [['' => ''], ['']],
            [
                ['aaa@host' => ''],
                true
            ],
            [
                ['aaa@host' => 'AAA', 'bbb.BBB@toshiba.co.jp' => '東芝'],
                true
            ],
            [
                ['aaa@host' => 123],
                ['aaa@host'],
            ],
            [null, false],
        ];
    }

    /**
    *   isValidMailAddress
    *
    *   @test
    *   @dataProvider isValidMailAddressProvidor
    */
    public function isValidMailAddress($data, $expect)
    {
//      $this->markTestIncomplete();

        $object = new MailMessage();
        $this->assertEquals(
            $expect,
            $this->callPrivateMethod($object, 'isValidMailAddress', [$data])
        );
    }

    public function isValidFromProvidor()
    {
        return [
            ['DUMMY', false],
            [[], false],     //diff false
            [['' => ''], ['']],
            [
                ['aaa@host' => ''],
                true
            ],
            [
                ['aaa@host' => 'AAA', 'bbb.BBB@toshiba.co.jp' => '東芝'],
                true
            ],
            [
                ['aaa@host' => 123],
                ['aaa@host'],
            ],
            [null, false],
        ];
    }

    /**
    *   isValidFrom
    *
    *   @test
    *   @dataProvider isValidFromProvidor
    */
    public function isValidFrom($data, $expect)
    {
//      $this->markTestIncomplete();

        $object = new MailMessage();
        $this->assertEquals(
            $expect,
            $this->callPrivateMethod($object, 'isValidFrom', [$data])
        );
    }

    /**
    *   isValidTo
    *
    *   @test
    *   @dataProvider isValidFromProvidor
    */
    public function isValidTo($data, $expect)
    {
//      $this->markTestIncomplete();

        $object = new MailMessage();
        $this->assertEquals(
            $expect,
            $this->callPrivateMethod($object, 'isValidTo', [$data])
        );
    }

    public function isValidCcProvidor()
    {
        return [
            ['DUMMY', false],
            [[], true],
            [['' => ''], ['']],
            [
                ['aaa@host' => ''],
                true
            ],
            [
                ['aaa@host' => 'AAA', 'bbb.BBB@toshiba.co.jp' => '東芝'],
                true
            ],
            [
                ['aaa@host' => 123],
                ['aaa@host'],
            ],
            [null, true],    //diff true
        ];
    }

    /**
    *   isValidCc
    *
    *   @test
    *   @dataProvider isValidCcProvidor
    */
    public function isValidCc($data, $expect)
    {
//      $this->markTestIncomplete();

        $object = new MailMessage();
        $this->assertEquals(
            $expect,
            $this->callPrivateMethod($object, 'isValidCc', [$data])
        );
    }

    /**
    *   isValidBcc
    *
    *   @test
    *   @dataProvider isValidCcProvidor
    */
    public function isValidBcc($data, $expect)
    {
//      $this->markTestIncomplete();

        $object = new MailMessage();
        $this->assertEquals(
            $expect,
            $this->callPrivateMethod($object, 'isValidBcc', [$data])
        );
    }

    public function isValidSubjectProvidor()
    {
        return [
            ["abcあいう\r123\n@!#", true],
            [123, false],
            [null, true],
            ['', true],
        ];
    }

    /**
    *   isValidSubject
    *
    *   @test
    *   @dataProvider isValidSubjectProvidor
    */
    public function isValidSubject($data, $expect)
    {
//      $this->markTestIncomplete();

        $object = new MailMessage();
        $this->assertEquals(
            $expect,
            $this->callPrivateMethod($object, 'isValidSubject', [$data])
        );
    }

    /**
    *   isValidMessage
    *
    *   @test
    *   @dataProvider isValidSubjectProvidor
    */
    public function isValidMessage($data, $expect)
    {
//      $this->markTestIncomplete();

        $object = new MailMessage();
        $this->assertEquals(
            $expect,
            $this->callPrivateMethod($object, 'isValidMessage', [$data])
        );
    }

    public function isValidAttachProvidor()
    {
        return [
            [null, true],
            ['DUMMY', false],
            [
                [
                    ['file' => '/aaa/bbb/ccc.txt']
                ],
                true
            ],
            [
                [
                    ['file' => '/aaa/bbb/ccc.txt', 'mime' => 'text/plain'],
                    ['file' => 'fff.htm'],
                ],
                true
            ],
            [
                [
                    ['file' => 'fff.htm'],
                    ['DUMMY' => '/aaa/bbb/ccc.txt', 'mime' => 'text/plain'],
                ],
                [1],
            ],
        ];
    }

    /**
    *   isValidAttach
    *
    *   @test
    *   @dataProvider isValidAttachProvidor
    */
    public function isValidAttach($data, $expect)
    {
//      $this->markTestIncomplete();

        $object = new MailMessage();
        $this->assertEquals(
            $expect,
            $this->callPrivateMethod($object, 'isValidAttach', [$data])
        );
    }

    public function isValidTypeProvidor()
    {
        return [
            ['text', true],
            ['html', true],
            ['TEXT', false],
            [123, false],
        ];
    }

    /**
    *   isValidType
    *
    *   @test
    *   @dataProvider isValidTypeProvidor
    */
    public function isValidType($data, $expect)
    {
//      $this->markTestIncomplete();

        $object = new MailMessage();
        $this->assertEquals(
            $expect,
            $this->callPrivateMethod($object, 'isValidType', [$data])
        );
    }

    public function isValidProvidor()
    {
        $params1 = [
            'from' => ['aaa@localhost' => 'AAA'],
            'to' => ['bbb@localhost' => 'BBB', 'b@localhost' => 'B'],
            'cc' => ['ccc@localhost' => 'CCC'],
            'bcc' => ['ddd@localhost' => 'DDD', 'd@localhost' => 'D'],
            'subject' => 'メールタイトル',
            'message' => '本文\r\n漢字も使う\r改行記号を変えた',
            'attach' => [
                [
                    'file' => __DIR__ . '\\MailMessageTest.php',
                    'mime' => 'plane/text'
                ],
                [
                    'file' => __DIR__ . '\\MailSwiftSmtpTest.php'
                ]
            ],
            'type' => 'text'
        ];

        $params2 = [
            'from' => ['aaa@' => 'AAA'],
            'to' => ['bbb@localhost' => 'BBB', '' => 'B'],
            'cc' => ['@localhost' => 'CCC'],
            'bcc' => ['ddd' => 'DDD', '@localhost' => 'D'],
            'subject' => 123,
            'message' => ['本文\r\n漢字も使う\r改行記号を変えた'],
            'attach' => [
                    [__DIR__ . '\\MailMessageTest.php'],
                ],
            'type' => 'xml'
        ];

        $err2 = [
            'from' => ['aaa@'],
            'to' => [''],
            'cc' => ['@localhost'],
            'bcc' => ['ddd', '@localhost'],
            'subject' => [''],
            'message' => [''],
            'attach' => [0],
            'type' => [''],
        ];

        return [
            [$params1, true, []],
            [$params2, false, $err2],
        ];
    }

    /**
    *   isValid
    *
    *   @test
    *   @dataProvider isValidProvidor
    */
    public function isValid($data, $expect, $err)
    {
//      $this->markTestIncomplete();

        $object = new MailMessage($data);
        $this->assertEquals($expect, $object->isValid());
        $this->assertEquals($err, $object->getValidError());
    }
}
