<?php

declare(strict_types=1);

namespace test\Concerto\excel\manual;

use Concerto\excel\ExcelDownloader;

//URL
//https://itcv1800005m.toshiba.local:8086/itc_work1/_mvc/dev_test/excel/manual/TestExcelDownloader.php

$file = implode(
    DIRECTORY_SEPARATOR,
    [
        __DIR__,
        'data',
        'ExcelDownloader1.xlsx',
    ],
);

$dl_name = 'テスト表.xlsx';

$obj = new ExcelDownloader();

$obj->send(
    $file,
    $dl_name
);

echo "download file name:{$dl_name}";
