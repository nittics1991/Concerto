<?php

declare(strict_types=1);

namespace test\Concerto\stream;

use test\Concerto\ConcertoTestCase;
use candidate\stream\StreamUtil;
use candidate\stream\StreamCallbackFilter;
use org\bovigo\vfs\vfsStream;

class StreamUtilTest extends ConcertoTestCase
{
    private $vfsRoot;
    private $vfsRootPath;
    public $namespace = 'Concerto.StreamUtil.';

    public function setUp(): void
    {
        //$this->vfsRoot = vfsStream::setup();
        //$this->vfsRootPath = vfsStream::url($this->vfsRoot->getName());

        $dir = [
            'test1.txt' => "abcd\r\nefgh\r\n"
        ];

        $this->vfsRoot = vfsStream::setup('root', null, $dir);
        $this->vfsRootPath = vfsStream::url('root');
    }

    /**
    *
    *
    */
    public function registerExceptionProvider()
    {
        return [
            [null],
            [1],
            [[2]],
        ];
    }

    /**
    *   @test
    *
    */
    public function register()
    {
//      $this->markTestIncomplete();

        $id1 = 'UTF-8/SJIS';
        $expect = $this->namespace . $id1;
        $this->assertEquals($expect, StreamUtil::register($id1));

        //重複登録
        $this->assertEquals($expect, StreamUtil::register($id1));

        //複数登録
        $id2 = 'SJIS/UTF-8';
        $expect = $this->namespace . $id2;
        $this->assertEquals($expect, StreamUtil::register($id2));

        //IDリスト
        $expect = [$id1, $id2];
        $this->assertEquals($expect, StreamUtil::getIdList());
    }

    /**
    *
    *
    */
    public function appendExceptionProvider()
    {
        $fp = fopen('php://memory', 'w');

        return [
            [null, 'strtoupper', 'aaa', STREAM_FILTER_ALL],
        ];
    }

    /**
    *   @test
    *   @dataProvider appendExceptionProvider
    */
    public function appendException($fp, $callback, $id, $read_write)
    {
//      $this->markTestIncomplete();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('append(stream, callback, name, read_write)');
        StreamUtil::append($fp, $callback, $id, $read_write);
    }

    /**
    *   @test
    *
    */
    public function append()
    {
//      $this->markTestIncomplete();

        $filename = $this->vfsRootPath . '/test1.txt';
        $fp = fopen($filename, 'r');

        //filter name
        $filterlist = stream_get_filters();
        $expect = $filterlist;
        $expect[] = "{$this->namespace}*";

        $filter1 = StreamUtil::append(
            $fp,
            function ($data) {
                return strtoupper($data);
            }
        );
        $actual = stream_get_filters();
        $this->assertEquals($expect, $actual);

        //data
        $data = file_get_contents($filename);
        $expect = explode("\r\n", strtoupper($data));
        $i = 0;

        while (($actual = fgets($fp)) !== false) {
            $this->assertEquals($expect[$i], $actual);
            $i++;
        }

        //追加フィルター
        $filter2 = StreamUtil::append(
            $fp,
            function ($data) {
                return ucfirst($data);
            },
            'ucfirst'
        );
        array_walk($expect, function ($val, $key) {
            return ucfirst($val);
        });

        rewind($fp);
        $i = 0;

        while (($actual = fgets($fp)) !== false) {
            $this->assertEquals($expect[$i], $actual);
            $i++;
        }

        //remove filter
        StreamUtil::remove($filter2);
        rewind($fp);
        $data = file_get_contents($filename);
        $expect = explode("\r\n", strtoupper($data));
        $i = 0;

        while (($actual = fgets($fp)) !== false) {
            $this->assertEquals($expect[$i], $actual);
            $i++;
        }
    }

    /**
    *   @test
    *
    */
    public function prepend()
    {
    //*   @runInSeparateProcessがあると動かない(phpunit bug)
        $this->markTestIncomplete();

        $filename = $this->vfsRootPath . '/test1.txt';
        $fp = fopen($filename, 'r');

        //filter name
        $filterlist = stream_get_filters();
        $expect = $filterlist;
        $expect[] = "{$this->namespace}*";
        $filter1 = StreamUtil::prepend(
            $fp,
            function ($data) {
                return strtoupper($data);
            }
        );
        $actual = stream_get_filters();
        $this->assertEquals($expect, $actual);

        //data
        $data = file_get_contents($filename);
        $expect = explode("\r\n", strtoupper($data));
        $i = 0;

        while (($actual = fgets($fp)) !== false) {
            $this->assertEquals($expect[$i], $actual);
            $i++;
        }

        //追加フィルター
        $filter2 = StreamUtil::prepend(
            $fp,
            function ($data) {
                return ucfirst($data);
            },
            'ucfirst'
        );
        array_walk($expect, function ($val, $key) {
            return ucfirst($val);
        });

        rewind($fp);
        $i = 0;

        while (($actual = fgets($fp)) !== false) {
            $this->assertEquals($expect[$i], $actual);
            $i++;
        }

        //remove filter
        StreamUtil::remove($filter1);
        rewind($fp);
        $data = file_get_contents($filename);
        $expect = explode("\r\n", ucfirst($data));
        $i = 0;

        while (($actual = fgets($fp)) !== false) {
            $this->assertEquals($expect[$i], $actual);
            $i++;
        }
    }

    /**
    *   @test
    *   @dataProvider appendExceptionProvider
    */
    public function prependException($fp, $callback, $id, $read_write)
    {
//      $this->markTestIncomplete();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('append(stream, callback, name, read_write)');
        StreamUtil::prepend($fp, $callback, $id, $read_write);
    }
}
