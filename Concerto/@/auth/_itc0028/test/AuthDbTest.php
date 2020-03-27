<?php

namespace Concerto\test\auth;

use Concerto\test\ConcertoTestCase;
use Concerto\auth\AuthDB;
use Concerto\auth\AuthDbBaseFactory;
use Concerto\auth\AuthConst;
use Concerto\database\LoginInf;
use Concerto\database\LoginInfData;
use Concerto\database\MstTanto;
use Concerto\database\MstTantoData;
use Concerto\standard\Session;

class AuthDBTest extends ConcertoTestCase
{
    
    protected function setUp(): void
    {
    }
    
    public function login1Provider()
    {
        return [
            ['99999itc', 'AAA', AuthConst::FAILURE],
            ['', 'AAA', AuthConst::DATAEMPTY],
            ['99999ITC', '', AuthConst::DATAEMPTY],
            ['99999ITC', 'AAA', AuthConst::AUTHENTICATED, '99999ITC'],
        ];
    }
    
    /**
    *   @test
    *   @dataProvider login1Provider
    **/
    public function login1($user, $pass, $expect, $authed = null)
    {
//      $this->markTestIncomplete();
        
        $factory = $this->createMock(AuthDbBaseFactory::class);
        
        $object = $this
            ->getMockBuilder(AuthDB::class)
            ->setConstructorArgs([$factory])
            ->setMethods(null)
            ->getMock();
        
        if (isset($authed)) {
            $session = new \StdClass();
            $session->user = $authed;
            $this->setPrivateProperty($object, 'session', $session);
        }
        
        $this->assertEquals($expect, $object->login($user, $pass));
    }
    
    public function login2Provider()
    {
        $user[0] = '12345ITC';
        $pass[0] = 'asdfg';
        
        $tanto = new \StdClass();
        $tanto->cd_tanto = $user[0];
        $tanto->nm_tanto = 'ASDFG';
        $tanto->kb_group = 'SHA12';
        $tanto->mail_add = "{$user[0]}@toshiba.co.jp";
        $tanto->cd_hash = password_hash($pass[0], PASSWORD_DEFAULT);
        $tanto->dt_hash = date('Ymd His');
        
        $tantolist[0] = $tanto;
        
        return [
            [$user[0], $pass[0], true, $tantolist, AuthConst::SUCCESS],
        ];
    }
    
    /**
    *   @test
    *   @dataProvider login2Provider
    **/
    public function login2($user, $pass, $isValid, $tantolist, $expect)
    {
//      $this->markTestIncomplete();
        
        $factory = $this->createMock(AuthDbBaseFactory::class);
        
        $mstTantoData = $this->createMock(MstTantoData::class);
        $mstTantoData
            ->expects($this->once())
            ->method('isValid')
            ->willReturn($isValid)
        ;
        
        $factory
            ->method('getMsttantoData')
            ->willReturn($mstTantoData)
        ;
        
        $mstTanto = $this->createMock(MstTanto::class);
        $mstTanto
            ->method('select')
            ->willReturn($tantolist)
        ;
        
        $factory
            ->expects($this->once())
            ->method('getMsttanto')
            ->willReturn($mstTanto)
        ;
        
        $loginInfData = $this->createMock(LoginInfData::class);
        $loginInfData
            ->expects($this->once())
            ->method('isValid')
            ->willReturn(true)
        ;
        
        $factory
            ->method('getLoginInfData')
            ->willReturn($loginInfData)
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
        
        $factory
            ->method('getLoginInf')
            ->willReturn($loginInf)
        ;
        
        $object = $this
            ->getMockBuilder(AuthDB::class)
            ->setConstructorArgs([$factory])
            ->setMethods(null)
            ->getMock();
        
        $session = $this->createMock(Session::class);
        $session
            ->expects($this->once())
            ->method('changeID')
        ;
        
        $this->setPrivateProperty($object, 'session', $session);
        
        $actual = $object->login($user, $pass);
        $this->assertEquals($expect, $actual);
    }
}
