<?php

declare(strict_types=1);

namespace test\Concerto\cache;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use test\Concerto\{
    ConcertoTestCase,
    TempDirTestHelper,
};
use Concerto\cache\FileCache;

class FileCacheTest extends ConcertoTestCase
{
    private TempDirTestHelper $TempHelper;

    protected function setUp(): void
    {
        $this->TempHelper = TempDirTestHelper::create(
            sys_get_temp_dir() .
            DIRECTORY_SEPARATOR .
            'FileCacheTest'
        );

        $this->TempHelper->clean();
    }

    /**
    */
    #[Test]
    public function basicSuccess()
    {
     // $this->markTestIncomplete('--- markTestInco    mplete ---');

        $obj = new FileCache($this->TempHelper->root());

        $actual = [
            'i_prop' => 123,
            's_prop' => 'string',
            'a_prop' => [1, 2, 3, 4, 5],
        ];

        foreach ($actual as $key => $val) {
            $this->assertEquals(
                false,
                $obj->has($key),
                "error:{$key}"
            );
            $this->assertEquals(false, $obj->has($key));
        }

        foreach ($actual as $key => $val) {
            $obj->set($key, $val, 0);
        }

        foreach ($actual as $key => $val) {
            $this->assertEquals(
                $actual[$key],
                $obj->get($key),
                "error:{$key}"
            );
        }

        foreach ($actual as $key => $val) {
            $this->assertEquals(
                true,
                $obj->has($key),
                "error:{$key}"
            );
        }

        foreach ($actual as $key => $val) {
            $this->assertEquals(
                true,
                $obj->delete($key),
                "error:{$key}"
            );
        }

        foreach ($actual as $key => $val) {
            $this->assertEquals(
                false,
                $obj->has($key),
                "error:{$key}"
            );
        }
    }

    /**
    */
    #[Test]
    public function multiSuccess()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new FileCache($this->TempHelper->root());

        $actual = [
            'i_prop' => 123,
            's_prop' => 'string',
            'a_prop' => [1, 2, 3, 4, 5],
        ];

        $this->assertEquals(true, $obj->setMultiple($actual));

        foreach ($actual as $key => $val) {
            $this->assertEquals(true, $obj->has($key));
        }

        $this->assertEquals($actual, $obj->getMultiple(array_keys($actual)));

        $obj->deleteMultiple(array_keys($actual));

        foreach ($actual as $key => $val) {
            $this->assertEquals(false, $obj->has($key));
        }
    }
}
