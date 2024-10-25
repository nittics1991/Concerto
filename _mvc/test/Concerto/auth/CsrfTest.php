<?php

declare(strict_types=1);

namespace test\Concerto\auth;

use test\Concerto\ConcertoTestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use Concerto\auth\Csrf;

class CsrfTest extends ConcertoTestCase
{
    private $class;

    protected function setUp(): void
    {
    }

    public function testGenerate()
    {

//      $this->markTestIncomplete('--- markTestIncomplete ---');

        @session_start();

        $token = Csrf::generate();
        $this->assertEquals(32, mb_strlen($token));
        $this->assertEquals(true, Csrf::isValid($token));
        $this->assertEquals(false, Csrf::isValid($token));

        $token = Csrf::generate(1, 8);
        $this->assertEquals(16, mb_strlen($token));
        $this->assertEquals(true, Csrf::isValid($token, false));
        $this->assertEquals(true, Csrf::isValid($token));

        $token = Csrf::generate();
        Csrf::remove($token);
        $this->assertEquals(false, Csrf::isValid($token));

        //タイミングによってはエラーになる可能性あり
        $expect = date('Ymd His', strtotime('+1 min'));
        $token = Csrf::generate(1);
        $this->assertEquals($expect, date('Ymd His', $_SESSION['csrf'][$token]));
    }

    /**
    */

    public function testGenerateException()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('invalid parameter');
        $token = Csrf::generate(10, 100);
    }
}
