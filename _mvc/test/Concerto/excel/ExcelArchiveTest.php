<?php

declare(strict_types=1);

namespace test\Concerto\excel;

use test\Concerto\ConcertoTestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use Concerto\excel\ExcelArchive;
use DOMDocument;
use RuntimeException;

class ExcelArchiveTest extends ConcertoTestCase
{
    public static function copyTemplateFile(
        string $template_file_name,
    ): string {
        $src = implode(
            DIRECTORY_SEPARATOR,
            [
                __DIR__,
                'data',
                $template_file_name,
            ],
        );

        $dest = implode(
            DIRECTORY_SEPARATOR,
            [
                sys_get_temp_dir(),
                uniqid() .
                '.' .
                    pathinfo(
                        $template_file_name,
                        PATHINFO_EXTENSION,
                    ),
            ],
        );

        $result = copy($src, $dest);

        if (!$result) {
            throw new RuntimeException(
                "work file copy error:{$template_file_name}"
            );
        }

        return $dest;
    }

    #[Test]
    public function main()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $file = 'ExcelArchive1.xlsx';

        $zip_file = self::copyTemplateFile($file);

        $obj = new ExcelArchive($zip_file);

        $domDocument = $obj->load('xl/sharedStrings.xml');

        $expect = <<< 'EOL'
        <?xml version="1.0" encoding="UTF-8" standalone="yes"?>
        <sst xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" count="8" uniqueCount="4"><si><t>A</t><phoneticPr fontId="1"/></si><si><t>B</t><phoneticPr fontId="1"/></si><si><t>C</t><phoneticPr fontId="1"/></si><si><t>D</t><phoneticPr fontId="1"/></si></sst>
        EOL;

        $this->assertEquals(
            $expect . "\n",
            $domDocument->saveXML(),
        );

        $addDocument = new DOMDocument();

        $addDocument->loadXML($expect);

        $save_path = 'xl/test.xml';

        $obj->save(
            $save_path,
            $addDocument,
        );

        $obj->close();

        $obj2 = new ExcelArchive($zip_file);

        $readDocument = $obj2->load($save_path);

        $this->assertEquals(
            $expect . "\n",
            $readDocument->saveXML(),
        );

        $obj2->close();
    }

    #[Test]
    public function addFile1()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $file = 'ExcelArchive1.xlsx';

        $zip_file = self::copyTemplateFile($file);

        $obj = new ExcelArchive($zip_file);

        $expect = <<< 'EOL'
        <?xml version="1.0" encoding="UTF-8" standalone="yes"?>
        <worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships" xmlns:mc="http://schemas.openxmlformats.org/markup-compatibility/2006" mc:Ignorable="x14ac xr xr2 xr3" xmlns:x14ac="http://schemas.microsoft.com/office/spreadsheetml/2009/9/ac" xmlns:xr="http://schemas.microsoft.com/office/spreadsheetml/2014/revision" xmlns:xr2="http://schemas.microsoft.com/office/spreadsheetml/2015/revision2" xmlns:xr3="http://schemas.microsoft.com/office/spreadsheetml/2016/revision3" xr:uid="{F10E6A90-2A76-471C-9217-FA0CE8255EF2}">
            <dimension ref="A1"/>
            <sheetViews>
                <sheetView tabSelected="1" workbookViewId="0"/>
            </sheetViews>
            <sheetFormatPr defaultRowHeight="15" x14ac:dyDescent="0.35"/>
            <sheetData>
                <row r="1">
                    <c r="A1"><v>123</v></c>
                </row>
            </sheetData>
            <phoneticPr fontId="1"/>
            <pageMargins left="0.7" right="0.7" top="0.75" bottom="0.75" header="0.3" footer="0.3"/>
        </worksheet>
        EOL;

        $work_file = implode(
            DIRECTORY_SEPARATOR,
            [
                sys_get_temp_dir(),
                uniqid(),
                'foo.xml',
            ],
        );

        mkdir(dirname($work_file));

        file_put_contents(
            $work_file,
            $expect,
        );

        $zip_path = 'xl/worksheets/sheet1.xml';

        $obj->addFile(
            $work_file,
            $zip_path,
        );

        $obj->close();

        $obj2 = new ExcelArchive($zip_file);

        $actual = $obj2->loadString($zip_path);

        $this->assertEquals(
            $expect,
            $actual,
        );

        $obj2->close();
    }
}
