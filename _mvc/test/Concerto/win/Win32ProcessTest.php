<?php

declare(strict_types=1);

namespace test\Concerto\win;

use test\Concerto\ConcertoTestCase;
use Concerto\win\Win32Process;
use VARIANT;

class Win32ProcessTest extends ConcertoTestCase
{
    protected function setUp(): void
    {
        if (
            !isset($_SERVER["OS"]) ||
            stripos($_SERVER["OS"], 'WINDOWS') === false
        ) {
            $this->markTestSkipped('Windows上でのみテスト実行');
            return;
        }
    }

    public function printToString($data)
    {
        ob_start();
        @print $data;
        $result = ob_get_contents();
        ob_end_clean();
        return $result;
    }

    /**
    * @test
    */
    public function findAll()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new Win32Process();
        $objectset = $obj->findAll();
        $this->assertEquals(true, count($objectset) > 0);
    }

    /**
    * @test
    */
    public function findByName()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new Win32Process();
        $process = $obj->findByName('System Idle Process');
        $this->assertEquals(1, count($process));
        $process2 = $process[0];
        $this->assertEquals(true, $process2 instanceof Win32Process);
    }

    /**
    * @test
    */
    public function findById()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new Win32Process();
        $process = $obj->findById(0);
        $this->assertEquals(true, $process instanceof Win32Process);
    }

    /**
    * @test
    */
    public function getter()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new Win32Process();
        $process = $obj->findByName('System Idle Process');
        $this->assertEquals(1, count($process));
        $process2 = $process[0];
        $this->assertEquals('System Idle Process', $process2->name);
        $this->assertEquals(0, $process2->processid);
    }

    /**
    * @test
    */
    public function terminate1()
    {
        $this->markTestIncomplete(
            '--- require manual operation ---'
        );

        $obj = new Win32Process();

        //実行ユーザ名取得
        $cliPid = getmypid();
        $process = $obj->findById($cliPid);
        $user = new Variant();
        $domain = new Variant();
        $process->getowner($user, $domain);
        $userName = $this->printToString($user);

        //手動notepadを1つ起動しておき、確認する
        $notepads = $obj->findByName('notepad.exe');
        $this->assertEquals(1, count($notepads));

        foreach ($notepads as $process) {
            $user = new Variant();
            $domain = new Variant();
            $process->getowner($user, $domain);
            $notepadOwner = @$this->printToString($user);

            if ($notepadOwner == $userName) {
                $process->terminate();
            }
        }
        $notepads = $obj->findByName('notepad.exe');
        $this->assertEquals(0, count($notepads));
    }
}
