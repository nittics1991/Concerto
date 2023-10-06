<?php

declare(strict_types=1);

namespace candidate_test\stream;

use test\Concerto\ConcertoTestCase;
use candidate\stream\MbEncodeFilter;
use org\bovigo\vfs\vfsStream;

class MbEncodeFilterTest extends ConcertoTestCase
{
    protected $vfsRoot;
    protected $vfsRootPath;
    
    protected function setUp(): void
    {
        if (
            !isset($_SERVER["OS"]) ||
            stripos($_SERVER["OS"], 'WINDOWS') === false
        ) {
            $this->markTestSkipped('Windows上でのみテスト実行');
            return;
        }

        $this->vfsRoot = vfsStream::setup();
        $this->vfsRootPath = vfsStream::url($this->vfsRoot->getName());
    }

    public function testStreamFilterWasRegistered()
    {
//      $this->markTestIncomplete();

        MbEncodeFilter::register();

        $this->assertContains('MbEncodeFilter.*', stream_get_filters());
    }

    public function testValidConversionParams()
    {
//      $this->markTestIncomplete();

        $stream  = fopen('data://text/plain;base64,', 'r');
        $filtername = 'MbEncodeFilter.US-ASCII/UTF-8';

        $success = (bool)stream_filter_append($stream, $filtername);

        $this->assertTrue(
            $success,
            'Failed to register valid conversion filter'
        );
    }

    public function testInvalidConversionParams()
    {
//      $this->markTestIncomplete();

        $stream  = fopen('data://text/plain;base64,', 'r');
        $filtername = 'MbEncodeFilter.FAKE/UTF-8';

        $success = (bool)@stream_filter_append($stream, $filtername);

        $this->assertNotTrue(
            $success,
            'Incorrectly registered invalid conversion filter'
        );
    }

    public function testDefaultConversionParam()
    {
//      $this->markTestIncomplete();

        $stream  = fopen('data://text/plain;base64,', 'r');
        $filtername = 'MbEncodeFilter.UTF-8';

        $success = (bool)stream_filter_append($stream, $filtername);

        $this->assertTrue(
            $success,
            'Failed to register conversion filter with default from encoding'
        );
    }

    public function testDefaultReplacementCharacterParam()
    {
//      $this->markTestIncomplete();

        $stream  = fopen('data://text/plain;base64,Zvxy', 'r');
        $filtername = 'MbEncodeFilter.UTF-8/UTF-8';

        stream_filter_append($stream, $filtername);

        $expected = 'f?r';
        $result   = stream_get_contents($stream);

        $this->assertSame(
            $expected,
            $result,
            'String did not contain correct replacement character'
        );
    }

    public function testReplacementCharacterParam()
    {
//      $this->markTestIncomplete();

        $stream  = fopen('data://text/plain;base64,VGVzdA==', 'r');
        $filtername = 'MbEncodeFilter.UTF-8/UTF-8';

        stream_filter_append($stream, $filtername, STREAM_FILTER_READ, 65533);

        $expected = 'Test';
        $result   = stream_get_contents($stream);

        $this->assertSame(
            $result,
            'Test',
            'String did not contain correct replacement character'
        );
    }

    public function testMultibyteEdgeHandling()
    {
//      $this->markTestIncomplete();

        $output  = fopen('php://memory', 'w+');
        $filtername = 'MbEncodeFilter.UTF-8/UTF-8';

        stream_filter_append($output, $filtername, STREAM_FILTER_WRITE);

        $donut_first_half  = substr("　", 0, 2);
        $donut_second_half = substr("　", 2);

        fwrite($output, $donut_first_half);
        fflush($output);

        rewind($output);

        $expected = '';
        $result   = stream_get_contents($output);

        $this->assertSame(
            $expected,
            $result,
            'Wrote out invalid character'
        );

        fseek($output, 2);

        fwrite($output, $donut_second_half);
        fflush($output);

        rewind($output);

        $expected = '　';
        $result   = stream_get_contents($output);

        $this->assertSame(
            $expected,
            $result,
            'Did not handle partial multibyte character'
        );
    }

    public function testCloseInvalidData()
    {
//      $this->markTestIncomplete();

        $output  = fopen('php://output', 'w');
        $filtername = 'MbEncodeFilter.UTF-8/UTF-8';

        stream_filter_append($output, $filtername);

        ob_start();

        fwrite($output, substr("　", 0, 2));
        fclose($output);

        $expected = '?';
        $result   = ob_get_clean();

        $this->assertSame(
            $expected,
            $result,
            'Did not correctly flush remaining invalid data'
        );
    }

    /**
    * @dataProvider unicodeMappingProvider
    */
    public function testCharsetConversion(
        $unicode_string,
        $charset,
        $charset_string
    ) {

        $input    = base64_encode($charset_string);
        $stream  = fopen('data://text/plain;base64,' . $input, 'r');
        $filtername = 'MbEncodeFilter.UTF-8/' . $charset;

        stream_filter_append($stream, $filtername);

        $expected = $unicode_string;
        $result   = stream_get_contents($stream);

        $this->assertSame(
            $expected,
            $result,
            'Failed to decode according to UCM file'
        );
    }

    public function unicodeMappingProvider()
    {
        $ucm_files = glob(__DIR__ . '/data/*.ucm');

        return array_combine($ucm_files, array_map(
            [$this, 'parseUcmFile'],
            $ucm_files
        ));
    }

    public function parseUcmFile($charset_filepath)
    {
        $charset_filename = basename($charset_filepath, '.ucm');
        $unicode_string   = '';
        $charset_string   = '';

        foreach (file($charset_filepath, FILE_IGNORE_NEW_LINES) as $line) {
            if (preg_match('/^<U(\w{4})> ((\\\\x\w{2})+)/', $line, $matches)) {
                $unicode_point = $matches[1];
                $hex_sequence  = $matches[2];

                preg_match_all('/\\\x(\w{2})/', $hex_sequence, $matches);

                $hex_codepoints = $matches[1];
                $char_sequence  = array_map('hex2bin', $hex_codepoints);

                $unicode_char = mb_convert_encoding(
                    "&#x$unicode_point;",
                    'UTF-8',
                    'HTML-ENTITIES'
                );

                $unicode_string .= $unicode_char;
                $charset_string .= implode('', $char_sequence);
            }
        }

        return [
            $unicode_string,
            $charset_filename,
            $charset_string
        ];
    }

    /**
    *   @test
    */
    public function sjis()
    {
        MbEncodeFilter::register();

        $filename = $this->vfsRootPath . DIRECTORY_SEPARATOR . 'aaa.csv';
        $fp = fopen($filename, 'w');
        stream_filter_append($fp, 'MbEncodeFilter.SJIS/UTF-8');

        $data = [
            "漢字を含む文字列\r\n",
            "5c文字列を含む「表示」は保存できるか?\r\n",
        ];

        foreach ($data as $val) {
            fwrite($fp, current($data));
            next($data);
        }
        fclose($fp);

        $contents = file_get_contents($filename);

        reset($data);
        foreach (explode("\r\n", $contents) as $str) {
            if ($str == '') {
                break;
            }
            $this->assertEquals(mb_convert_encoding(current($data), 'SJIS', 'UTF-8'), "{$str}\r\n");
            next($data);
        }

        //read
        $fp = fopen($filename, 'r');
        stream_filter_append($fp, 'MbEncodeFilter.UTF-8/SJIS');

        reset($data);
        while ($str = fgets($fp)) {
            $this->assertEquals(current($data), $str);
            next($data);
        }
        fclose($fp);
    }
}
