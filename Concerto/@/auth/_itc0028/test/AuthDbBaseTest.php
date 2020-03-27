<?php

namespace Concerto\test\auth;

use Concerto\test\ConcertoTestCase;
use Concerto\auth\AuthDbBase;
use Concerto\auth\AuthDbBaseFactory;
use DateTimeImmutable;
use Concerto\database\LoginInf;
use Concerto\database\LoginInfData;

class StubAuthDbBaseTest extends AuthDbBase
{
    /**
    *   {inherit}
    **/
    public function login($user, $password)
    {
    }
}

////////////////////////////////////////////////////////

class AuthDbBaseTest extends ConcertoTestCase
{
    protected $object;
    
    protected function setUp(): void
    {
        $this->object = $this
            ->getMockBuilder(StubAuthDbBaseTest::class)
            ->disableOriginalConstructor()
            ->setMethods(null)
            ->getMock();
    }
    
    public function isValidLogDayProvider()
    {
        return [
            [12, true],
            [0, true],
            [-3, false],
            ['12', false],
        ];
    }
    
    /**
    *   @test
    *   @dataProvider isValidLogDayProvider
    **/
    public function isValidLogDay($data, $expect)
    {
//      $this->markTestIncomplete();
        
        $actual = $this->callPrivateMethod($this->object, 'isValidLogDay', [$data]);
        $this->assertEquals($expect, $actual);
    }
    
    public function isValidExpirationDayProvider()
    {
        return [
            [12, true],
            [0, true],
            [-3, false],
            ['12', false],
        ];
    }
    
    /**
    *   @test
    *   @dataProvider isValidExpirationDayProvider
    **/
    public function isValidExpirationDay($data, $expect)
    {
//      $this->markTestIncomplete();
        
        $actual = $this->callPrivateMethod($this->object, 'isValidExpirationDay', [$data]);
        $this->assertEquals($expect, $actual);
    }
    
    public function setConfigProvider()
    {
        $data[0] = ['logDay' => 12, 'expirationDay' => 3];
        $data[1] = ['logDay' => 0, 'expirationDay' => 3];
        $data[2] = ['logDay' => 2, 'expirationDay' => 0];
        $data[3] = ['logDay' => 'X', 'expirationDay' => 4];
        $data[4] = ['logDay' => 5, 'expirationDay' => 'x'];
        
        return [
            [$data[0], array_values($data[0])],
            [$data[1], array_values($data[1])],
            [$data[2], array_values($data[2])],
            [$data[3], [9999, $data[3]['expirationDay']]],
            [$data[4], [$data[4]['logDay'], 9999]],
        ];
    }
    
    /**
    *   @test
    *   @dataProvider setConfigProvider
    **/
    public function setConfig($data, $expect)
    {
//      $this->markTestIncomplete();
        
        $this->callPrivateMethod($this->object, 'setConfig', [$data]);
        $actual[] = $this->getPrivateProperty($this->object, 'logDay');
        $actual[] = $this->getPrivateProperty($this->object, 'expirationDay');
        $this->assertEquals($expect, $actual);
    }
    
    public function confirmPasswordExpirationProvider()
    {
        $data[0] = date('Ymd His');
        $data[1] = date('Ymd His', strtotime('+1 day'));
        $data[2] = date('Ymd His', strtotime('-1 day'));
        $data[3] = date('Ymd His', strtotime('-2 day'));
        
        return [
            [new DateTimeImmutable($data[0]), 1, true],
            [new DateTimeImmutable($data[1]), 1, true],
            // [new DateTimeImmutable($data[2]), 1, true],  //sec単位で失敗する場合がある
            [new DateTimeImmutable($data[3]), 1, false],
        ];
    }
    
    /**
    *   @test
    *   @dataProvider confirmPasswordExpirationProvider
    **/
    public function confirmPasswordExpiration($data, $expire, $expect)
    {
//      $this->markTestIncomplete();
        
        $object = clone $this->object;
        
        $actual = $this->setPrivateproperty(
            $object,
            'expirationDay',
            $expire
        );
        
        $actual = $this->callPrivateMethod(
            $object,
            'confirmPasswordExpiration',
            [$data]
        );
        $this->assertEquals($expect, $actual);
    }
    
    public function setLoginLogProvider()
    {
        $ids[0] = '123';
        $names[0] = 'AAA';
        
        
        return [
            [$ids[0], $names[0], true],
        ];
    }
    
    /**
    *   @test
    *   @dataProvider setLoginLogProvider
    **/
    public function setLoginLog($id, $name, $expect)
    {
//      $this->markTestIncomplete();
        
        $loginInfData = $this->createMock(LoginInfData::class);
        $loginInfData
            ->expects($this->once())
            ->method('isValid')
            ->willReturn(true)
        ;
        
        $loginInf = $this->createMock(LoginInf::class);
        $loginInf
            ->expects($this->once())
            ->method('insert')
        ;
        $loginInf
            ->expects($this->once())
            ->method('deletePastDate')
        ;
        
        $factory = $this->createMock(AuthDbBaseFactory::class);
        $factory->method('getLoginInfData')
            ->willReturn($loginInfData)
        ;
        
        $factory->method('getLoginInf')
            ->willReturn($loginInf)
        ;
        
        $object = new StubAuthDbBaseTest($factory);
        
        $actual = $this->callPrivateMethod(
            $object,
            'setLoginLog',
            [$id, $name]
        );
        $this->assertEquals($expect, $actual);
    }
    
    public function getMethodProvider()
    {
        return [
            [123]
        ];
    }
    
    /**
    *   @test
    *   @dataProvider getMethodProvider
    **/
    public function getMethod($data)
    {
//      $this->markTestIncomplete();
        
        $object = clone $this->object;
        
        $session = new \StdClass();
        $session->user = $data;
        $this->setPrivateproperty($object, 'session', $session);
        
        $this->assertEquals($data, $object->user);
    }
}
