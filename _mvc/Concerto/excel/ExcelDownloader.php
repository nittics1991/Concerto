<?php

/**
*   ExcelDownloader
*
*   @version 240919
*/

declare(strict_types=1);

namespace Concerto\excel;

use RuntimeException;

class ExcelDownloader
{
    /**
    *   send
    *
    *   @param string $excel_path
    *   @param string $file_name
    *   @return void
    */
    public static function send(
        string $excel_path,
        string $file_name,
    ): void {

        if (headers_sent()) {
            throw new RuntimeException(
                "already sent http header"
            );
        }

        $download_filename = str_replace(
            ["\0","\r","\n","\t",'"'],
            '',
            $file_name
        );

        $file_size = filesize($excel_path);

        $fp = fopen($excel_path, 'rb');

        if ($fp === false) {
            throw new RuntimeException(
                "zip file open error:{$excel_path}"
            );
        }

        $content_type = pathinfo(
            $excel_path,
            PATHINFO_EXTENSION
        ) === 'xlsm' ?
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' :
            'application/vnd.ms-excel.sheet.macroenabled.12';

        header(
            'Content-type: ' . $content_type . "'",
        );

        header(
            'Content-Disposition: attachment; filename="' .
                $download_filename .
                '"',
        );

        header('Content-Length: ' . $file_size);

        ob_end_clean();

        rewind($fp);

        fpassthru($fp);

        fclose($fp);
    }
}
