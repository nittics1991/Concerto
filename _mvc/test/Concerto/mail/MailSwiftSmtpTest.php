<?php

/**
*   smtp4dev起動している事
*
*/

declare(strict_types=1);

namespace test\Concerto\mail;

use Closure;
use StdClass;
use Swift_Mailer;
use test\Concerto\ConcertoTestCase;
use Concerto\mail\MailMessage;
use Concerto\mail\MailSwiftSmtp;

class MailSwiftSmtpTest extends ConcertoTestCase
{
    private $object;

    public function setUp(): void
    {
        $this->object = new MailSwiftSmtp([
            'host' => '127.0.0.1',
            'port' => 25,
            'user' => null,
            'password' => null,
            'security' => null
        ]);
    }

    /**
    *   setMailerException
    *
    *   @test
    */
    public function setMailerException()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('invalid parameters');
        $params = [
            'host' => '127.0.0.1',
            'port' => 25,
            'dummy' => 'DUMMY',
            'user' => null,
            'password' => null,
            'security' => null
        ];

        $object = new MailSwiftSmtp($params);
    }

    public function setMailerProvider()
    {
        return [
            [
                [
                    'host' => '133.113.128.1',
                    'port' => 44,
                    'user' => 'User',
                    'password' => 'Pass',
                    'security' => 'security'
                ],
                [
                    'host' => '133.113.128.1',
                    'port' => 44,
                    'user' => 'User',
                    'password' => 'Pass',
                    'security' => 'security'
                ],
            ],
            [
                [
                    'host' => 'mail.toshiba.co.jp',
                    'port' => 44,
                ],
                [
                    'host' => 'mail.toshiba.co.jp',
                    'port' => 44,
                    'user' => null,
                    'password' => null,
                    'security' => null
                ],
            ],
            [
                [
                    'host' => '133.113.128.1',
                    'port' => '44',
                ],
                [
                    'host' => '133.113.128.1',
                    'port' => 25,
                    'user' => null,
                    'password' => null,
                    'security' => null
                ],
            ],
        ];
    }

    /**
    *   setMailer
    *
    *   @test
    *   @dataProvider setMailerProvider
    */
    public function setMailer($params, $expect)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $object = new MailSwiftSmtp($params);
        $this->assertEquals($expect, $this->getPrivateProperty($object, 'params'));
        $this->assertInstanceOf(Swift_Mailer::class, $this->getPrivateProperty($object, 'mailer'));
    }

    public function sendByMessageErrorProvider()
    {
        $stdClass = new StdClass();
        $invalidMessage = new MailMessage([
            'from' => []
        ]);

        return [
            [
                'DUMMY', 'DUMMY'],
                [$stdClass, $stdClass],
                [$invalidMessage, $invalidMessage],
        ];
    }

    /**
    *   message data
    *
    *   @test
    *   @dataProvider sendByMessageErrorProvider
    */
    public function sendByMessageError($message, $expect)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertEquals($expect, $this->object->send($message));
    }

    public function sendProvider()
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
                    'file' =>
                        __DIR__ . DIRECTORY_SEPARATOR .
                        implode(
                            DIRECTORY_SEPARATOR,
                            ['MailMessageTest.php'],
                        ),
                    'mime' => 'plane/text'
                ],
                [
                    'file' =>
                        __DIR__ . DIRECTORY_SEPARATOR .
                        implode(
                            DIRECTORY_SEPARATOR,
                            ['MailSwiftSmtpTest.php'],
                        ),
                ]
            ],
            'type' => 'text'
        ];

        $params2 = [
            'from' => ['zzz@localhost' => 'ZZZ'],
            'to' => ['yyy@localhost' => 'YYY'],
            'cc' => ['zzz@gmail.com' => '谷田　正雄CC1','bunpabcom@gmail.com' => '谷田　正雄CC2'],
            'bcc' => [],
            'subject' => 'HTMLメールタイトル',
            'message' => '
                <!DOCTYPE html>
                <html lang="ja">
                <head>
                <meta charset="UTF-8">
                </head>
                <body>
                    <h4>メールテスト</h4>
                    <div>
                        HTMLメールテスト
                        本文をHTML5で作成
                    </div>
                </body>
                </html>
            ',
            'attach' => [
                [
                    'file' =>
                        __DIR__ . DIRECTORY_SEPARATOR .
                        implode(
                            DIRECTORY_SEPARATOR,
                            ['MailMessageTest.php'],
                        ),
                    'mime' => 'plane/text'
                ],
                [
                    'file' =>
                        __DIR__ . DIRECTORY_SEPARATOR .
                        implode(
                            DIRECTORY_SEPARATOR,
                            ['MailSwiftSmtpTest.php'],
                        ),
                ]
            ],
            'type' => 'html'
        ];

        return [
            [new MailMessage($params1), true],
            [new MailMessage($params2), true]
        ];
    }

    /**
    *   message data
    *
    *   @test
    *   @dataProvider sendProvider
    */
    public function send($message, $expect)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertEquals($expect, $this->object->send($message));
    }
}
