<?php

declare(strict_types=1);

namespace test\Concerto\test;

use test\Concerto\{
    ConcertoTestCase,
    TempDirTestHelper,
};

class TempDirTestHelperTest extends ConcertoTestCase
{
    public function constructProvider()
    {
        $dirs = [
            sys_get_temp_dir() .
            DIRECTORY_SEPARATOR .
            'test',
        ];

        return [
            [
                $dirs[0],
                0777,
                true,
                null,
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider constructProvider
    */
    public function construct(
        ?string $path,
        int $permissions,
        bool $recursive,
        $context,
    ) {
        //$this->markTestIncomplete('--- markTestIncomplete ---');

        try {
            $obj = new TempDirTestHelper(
                $path,
                $permissions,
                $recursive,
                $context,
            );

            $this->assertEquals(1, 1);

            $this->assertEquals(
                $path,
                $obj->root(),
            );
        } catch (\Exception $e) {
            $this->assertEquals(1, 0);
        }
    }

    /**
    *   @test
    *   @dataProvider constructProvider
    */
    public function create(
        ?string $path,
        int $permissions,
        bool $recursive,
        $context,
    ) {
        //$this->markTestIncomplete('--- markTestIncomplete ---');

        try {
            $obj = TempDirTestHelper::create(
                $path,
                $permissions,
                $recursive,
                $context,
            );

            $this->assertEquals(1, 1);

            $this->assertEquals(
                $path,
                $obj->root(),
            );
        } catch (\Exception $e) {
            $this->assertEquals(1, 0);
        }
    }

    /**
    *   @test
    *   @dataProvider constructProvider
    */
    public function clean(
        ?string $path,
        int $permissions,
        bool $recursive,
        $context,
    ) {
        //$this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = TempDirTestHelper::create(
            $path,
            $permissions,
            $recursive,
            $context,
        );

        $child_path = $obj->root() .
            DIRECTORY_SEPARATOR .
            'child1/child11';

        mkdir(
            $child_path,
            0777,
            true
        );

        touch(
            $child_path .
            DIRECTORY_SEPARATOR .
            'file11a.txt'
        );

        $this->assertEquals(
            false,
            $obj->isEmptyDir($path),
        );

        $obj->clean();

        $this->assertEquals(
            true,
            $obj->isEmptyDir($path),
        );
    }
}
